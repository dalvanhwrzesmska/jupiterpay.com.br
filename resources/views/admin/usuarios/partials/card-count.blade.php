@props([
    'label',
    'info',
    'icon'
    ])

<div class="mb-4 col-xxl-3 col-md-6">
    <div class="border-4 card card-raised card-border-color ">
        <div class="px-4 card-body" style="min-height: 114px">
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <div class="me-2">
                    <div class="display-5">{{ $info }}</div>
                    <div class="card-text">{{ $label }}</div>
                </div>
                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid {{ $icon }}"></i></div>
            </div>
        </div>
    </div>
</div>
