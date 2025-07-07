<script type="text/javascript">
// Build the chart

$(".accordion-header").click(function() {
    for (i = 0; i < $(this).length; i++) {
        $(this).toggleClass('active');
        $(this).find('.spin').toggleClass('spin-effect');
        $(this).next('.accordion-panel').toggleClass('active');
    }
});

var year = $('#yearGraph').val();
        getGraph(year);


$('#yearGraph').on('change', function(){
    year = $(this).val();
    getGraph(year);
});


var del = $('.delete-notif').modal({
    header:'Hapus Notifikasi',
    render: function(el,data) {
        el.html('<div class="blockWrapper"><span>Apakah anda yakin ingin menghapus notifikasi?<span><div class="form"></div><div>');
        data.onSuccess = function(){
            location.reload();
        }
        data.isReset = true;
        $('.form', el).form(data).data('form');
    }
})




// CALLING THE GRAPHIC
function getGraph(year){
    console.log(year);
    $.ajax({
        url:'<?php echo site_url('main/rekapPerencanaanGraph') ?>/'+year,
        method: 'post',
        async:false,
        success: function(__graph) {
            __graph = JSON.parse(__graph);

           Highcharts.chart('graph_', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },

                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                    }
                },
                series: [{
                    name: 'Perencanaan Pengadaan',
                    colorByPoint: true,
                    data: [{
                    name: 'Perencanaan ',
                    y: __graph.plan,
                    }, {
                    name: ' Aktual',
                    y: __graph.act
                    }]
                }]
                });
        }
    })
}

</script>
