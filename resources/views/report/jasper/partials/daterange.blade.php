<div class="form-group">
    <label for="">Date Range</label>
    <div id="" class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i data-feather="calendar" width='18' height='18' class=" text-primary"></i></span>
        </div>
        <input type="text" placeholder='YYYY-MM-DD to YYYY-MM-DD' name="date" class="form-control bg-transparent" value='{{isset($from,$to)? $from != '' ?$from.' to '.$to  : '': ''  }}'  autocomplete="off" id="filter_date_range">
    </div>
</div>

<script>
    var date_format = 'YYYY-MM-DD';
    function applyDateRangePicker(id, start, end){
        $('#filter_date_range').daterangepicker(
            {
                startDate: start,
                endDate: end,

                ranges: {
                    // 'Default': [],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                    // 'Last 5 Year': [moment().subtract(5, 'year').startOf('year'), moment().subtract(5, 'year').endOf('year')],
                },
                locale: {
                    cancelLabel: 'Clear',
                    format: date_format,
                },
                // "singleDatePicker": true,
                // autoApply: true,
                alwaysShowCalendars: true,
                // autoUpdateInput: false,
                showDropdowns: true,
                applyButtonClasses: "btn-success",
                cancelClass: "btn-secondary"
            },
            function(start, end, label) {
                // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                // if(start.length > 0 && end.length > 0){
                    $('#filter_date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                // }else{
                //     $('#filter_date_range').val('');
                // }

            }
        );
    }
    
    // function updateUrlParameters(params, setHistory=false) {
    //     var url = new URL(window.location.href);
    //     Object.keys(params).forEach(key => url.searchParams.set(key, params[key]));
    //     if(setHistory){
    //         window.history.replaceState(null, null, url);
    //     }else{
    //         window.location.href = url;
    //     }
    // }
    // function removeUrlParameters(params) {
    //     var url = new URL(window.location.href);
    //     params.forEach(param => url.searchParams.delete(param));
    //     window.location.href = url;
    // }




    $(document).ready(function(){
        @if(isset($_GET['start_date']) && isset($_GET['start_date']))
            var start = moment("{{$_GET['start_date']??''}}",date_format);
            var end = moment("{{$_GET['end_date']??''}}",date_format);
            applyDateRangePicker('#filter_date_range', start, end);

        @else
            var start = moment().startOf('year');
            var end = moment().endOf('year');
            
            // var start = '';
            // var end = '';
            applyDateRangePicker('#filter_date_range', start, end);
            $('#filter_date_range').val('');

        @endif

        // Set initial value to null

        // Event to clear the input field when the cancel button is clicked
        $('#filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });



        // $('#filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        //     // applyDateRangePicker('#filter_date_range', start, end)
        //     // console.log(ev, picker)
        //     // $('#filter_date_range').val('');
        //     // applyDateRangePicker('#filter_date_range', start, end);

        //     updateUrlParameters({
        //         start_date: moment().startOf('year').format(date_format),
        //         end_date: moment().endOf('year').format(date_format)
        //     });
        //     // removeUrlParameters([
        //     //     'start_date',
        //     //     'end_date'
        //     // ]);
        // });
        // $('#filter_date_range').on('apply.daterangepicker', function(ev, picker) {
        //     // console.log(picker.startDate.format('YYYY-MM-DD'));
        //     // console.log(picker.endDate.format('YYYY-MM-DD'));
        //     // if(picker.startDate.format('YYYY-MM-DD') != "" && picker.endDate.format('YYYY-MM-DD') != ""){
        //         updateUrlParameters({
        //             start_date: picker.startDate.format('YYYY-MM-DD'),
        //             end_date: picker.endDate.format('YYYY-MM-DD')
        //         });
        //     // }else{
        //     //     removeUrlParameter([
        //     //         'start_date',
        //     //         'end_date'
        //     //     ]);
        //     // }
            
            
        // });
    });


    // charts
    var colors = {
        red:"#f41127",
        purple:"#8833ff",
        white:"#fff",
        green:"#46c35f",
        yellow:"#ffdd00",
    };
</script>