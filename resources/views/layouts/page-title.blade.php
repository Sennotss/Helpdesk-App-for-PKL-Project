<h4 class="py-2 mb-2">
  @php
    $currentRouteName = Route::currentRouteName();
  @endphp

  @if (!empty($menuData))
      @foreach ($menuData[0]->menu as $item)
          @if (!empty($item->slug) && $currentRouteName === $item->slug)
              <h2>
                  <span class="text-muted fw-light small">{{ $item->name }} /</span>
              </h2>
          @endif
          @if (!empty($item->submenu))
              @foreach ($item->submenu as $sub)
                  @if (!empty($sub->slug) && $currentRouteName === $sub->slug)
                      <h2>
                          <span class="text-muted fw-light small">{{ $item->name }} /</span> {{ $sub->name }}
                      </h2>
                  @endif
              @endforeach
          @endif
      @endforeach
  @endif
</h4>

