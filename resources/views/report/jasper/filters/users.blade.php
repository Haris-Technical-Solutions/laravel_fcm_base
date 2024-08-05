<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<div class="card no-print" >
    <h6 class="card-title pt-4 pl-4" >
        @include('report.jasper.partials.export')
        <span  data-toggle="collapse" href="#{{$filter}}" aria-controls="{{$filter}}" role="button" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter text-primary"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            All Requests Filter
        </span>
    </h6>
    <div id="{{$filter}}" class=""  >
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="form-group">
                        <label for="">Request No</label>
                        <input type="text" name="user_id" id="user_id" placeholder="REQ-0000" autofocus class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('report.jasper.partials.functions')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script>
    var filter_fields = [
        {
            'field':'user_id',
            'type':'input'
        }
    ]
</script>

