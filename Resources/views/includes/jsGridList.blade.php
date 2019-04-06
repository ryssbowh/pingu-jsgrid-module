<div class="card jsGridList">
    <div class="card-header">
    	{{ $title }} <span style="display:none">(total: <span class="jsgrid-total"></span>)</span>
    	@if($addUrl)
    		<a class="add" href="{{ $addUrl }}">Add new</a>
    	@endif
    </div>

    <div class="card-body">
        @include('core::includes.contextualLinks')
        @include('jsgrid::includes.jsGridTable')
    </div>
</div>