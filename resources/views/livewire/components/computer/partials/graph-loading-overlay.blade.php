@if ($isLoadingGraph)
    <div
        class="top-0 position-absolute start-0 d-flex flex-column align-items-center justify-content-center w-100 h-100 bg-white bg-opacity-75 rounded"
        style="z-index: 5;">
        <span class="mb-2 spinner-border spinner-border-sm text-primary" role="status"></span>
        <span class="text-gray-600 fs-7 fw-semibold">Loading graph data...</span>
    </div>
@endif
