@if (session('status'))
                <div class="alert alert-{{ session('status') }} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-{{ session('status')=='success' ? 'check' : 'ban' }}"></i>  {{ session('status') }}!</h4>
                    {{ session('message') }}
                </div>
@endif
@if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i>   {{__("Error !!")}}</h4>
                    {{ session('error') }}
                </div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-ban"></i> {{__("Error !!")}}</h4>
    <ul><li>{!!join("</li><li>",$errors->all()) !!}</li></ul>
</div>
@endif
