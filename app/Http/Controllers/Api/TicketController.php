<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketImages;
use App\Models\TicketLinks;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with(['images', 'links', 'user', 'problem', 'application'])->orderBy('created_at', 'desc')->get();

        return ApiResponse::success($tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'problem_id' => 'nullable|exists:problems,id',
        'application_id' => 'nullable|exists:applications,id',
        'client' => 'nullable|string',
        'issue' => 'required|string',
        'description' => 'required|string',
        'assigned_to' => 'nullable|exists:users,id',
        'priority' => 'nullable|in:low,middle,high',
        'via' => 'nullable|string',
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'links' => 'nullable|array',
        'links.*' => 'url'
      ]);

      if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 422);
      }

      $ticket = Ticket::create([
          'problem_id' => $request->problem_id,
          'application_id' => $request->application_id,
          'user_id' => auth()->id(),
          'client' => $request->client,
          'issue' => $request->issue,
          'description' => $request->description,
          'status' => 'open',
          'assigned_to' => $request->assigned_to,
          'priority' => $request->priority,
          'via' => $request->via ?? 'web',
      ]);

      if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('ticket_images', 'public');
            TicketImages::create([
                'ticket_id' => $ticket->id,
                'image_path' => $path
            ]);
        }
      }


      if ($request->links) {
          foreach ($request->links as $link) {
              TicketLinks::create([
                  'ticket_id' => $ticket->id,
                  'url' => $link
              ]);
          }
      }

      return response()->json([
          'message' => 'Tiket berhasil dibuat.',
          'ticket' => $ticket->load(['images', 'links'])
      ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_code)
    {
      try {
          $ticket = Ticket::with(['images', 'links', 'user', 'problem', 'application'])
              ->where('ticket_code', $ticket_code)
              ->first();

          if (!$ticket) {
              return ApiResponse::notFound('Ticket Not Found');
          }

          return ApiResponse::success($ticket, "Data " . $ticket_code . " berhasil diambil", 200);
      } catch (\Exception $e) {
          return ApiResponse::error('Internal Server Error: ' . $e->getMessage());
      }
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateTicket(Request $request, $ticket_code)
    {
      $validator = Validator::make($request->all(), [
        'status' => 'nullable|in:open,onprogress,resolved,revition',
        'assigned_to' => 'nullable|exists:users,id',
        'application_id' => 'nullable|exists:applications,id',
        'problem_id' => 'nullable|exists:problems,id',
        'priority' => 'nullable|in:low,middle,high',
      ]);

      if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
      }

      $ticket = Ticket::where('ticket_code', $ticket_code)->firstOrFail();

      $ticket->update([
          'status' => $request->status,
          'assigned_to' => $request->assigned_to,
          'application_id' => $request->application_id,
          'problem_id' => $request->problem_id,
          'priority' => $request->priority,
      ]);

      return ApiResponse::success($ticket, 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function notifyTelegram($id)
    {
        $tiket = Ticket::findOrFail($id);

        $token = config('services.telegram.bot_token');
        $chat_id = config('services.telegram.chat_id');

        $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chat_id,
            'text' => "
              *Tiket Baru Masuk!*
              Client: {$tiket->client}
              Issue: {$tiket->issue}
              Keterangan: {$tiket->description}
              Tanggal: " . now()->format('d M Y H:i'),
            'parse_mode' => 'Markdown'
        ]);

        return response()->json([
            'status' => 'success',
            'telegram_response' => $response->json()
        ]);
    }

}
