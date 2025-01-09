(function($) {
  "use strict";
  
loadStaticData();
var messagesTransactionDays=[];
var messagesTransactionValues=[];

$('#period a').on('click', function() {
        var days = $(this).data('value');
        fetchMessagesData(days);
    });

$('#meter a').on('click', function() {
        var days = $(this).data('value');
        fetchBotData(days);
        fetchBulkData(days);
        fetchSingleData(days);
    });



function loadStaticData() {
  const url = $('#static-data').val();
  const base_url = $('#base_url').val();

  $.ajax({
    type: 'get',
    url: url,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData:false,

    success: function(response){ 
      $('#total-device').html(response.cloudapisCount);
      $('#total-messages').html(response.messagesCount);
      $('#total-contacts').html(response.contactCount);
      $('#total-schedule').html(response.scheduleCount);
      $('#message-analysis').html(response.messagesAnalysis);
      $('#read-count').html(response.readCount);
      $('#delivered-count').html(response.deliveredCount);
      $('#failed-count').html(response.failedCount);
      $('#chatbot-count').html(response.chatbotMeter);
      $('#bulk-count').html(response.bulkMeter);
      $('#single-count').html(response.singleMeter);
      
      
      
      
    }
  });
}
function fetchMessagesData(days) {
        const base_url = $('#base_url').val();
        const url = base_url + '/user/messages-transaction/' + days;
        var totalCount = 0;
        var readCount = 0;
        var deliveredCount = 0;
        var failedCount = 0;
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                console.log(response);
                response.forEach(function(item) {
                totalCount += item.smstransactions;
                readCount += item.read_count;
                deliveredCount +=item.delivered_count;
                failedCount += item.failed_count;
            });

                // Update the UI or do further processing here
                const analysis = `<span class="light text-primary font-12">${totalCount} Message has sent </span>in last ${days} Days`;
                $('#analysis').html(analysis);
                $('#read-count').html(readCount);
                $('#delivered-count').html(deliveredCount);
                $('#failed-count').html(failedCount);
            }
        });
    }
    
    function fetchBotData(days) {
        const base_url = $('#base_url').val();
        const url = base_url+'/user/chatbot-transaction/'+days;
        var totalCount = 0;
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                console.log(response);
                response.forEach(function(item) {
                totalCount += item.smstransactions;
            });

                // Update the UI or do further processing here
                $('#chatbot-count').html(totalCount);
            }
        });
    }
    
    function fetchBulkData(days) {
        const base_url = $('#base_url').val();
        const url = base_url+'/user/bulk-transaction/'+days;
        var totalCount = 0;
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                console.log(response);
                response.forEach(function(item) {
                totalCount += item.smstransactions;
            });

                // Update the UI or do further processing here
                $('#bulk-count').html(totalCount);
            }
        });
    }
    
    function fetchSingleData(days) {
        const base_url = $('#base_url').val();
        const url = base_url+'/user/single-transaction/'+days;
        var totalCount = 0;
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                console.log(response);
                response.forEach(function(item) {
                totalCount += item.smstransactions;
            });

                // Update the UI or do further processing here
                $('#single-count').html(totalCount);
            }
        });
    }


  try {

    var options15 = {
      series: [{
        name: 'Performance Matrix',
        data: [0, 300, 700, 1500]
      },],
      chart: {
        height: 230,
        type: 'area',
        fontFamily: 'Poppins, sans-serif',
        toolbar: {
          show: false
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth'
      },
      xaxis: {
        labels: {
          show: false,
        },
        axisBorder: {
          show: false,
        },
        axisTicks: {
          show: false,
        },
        tooltip: {
          enabled: false,
        },
      },
      yaxis: {
        labels: {
          show: false,
        },
        axisBorder: {
          show: false,
        },
        axisTicks: {
          show: false,
        },
        tooltip: {
          enabled: false,
        },
      },
      grid: {
        xaxis: {
          lines: {
            show: false
          }
        },
        yaxis: {
          lines: {
            show: false,
          }
        },
        padding: {
          top: 0,
          right: 0,
          bottom: 0,
          left: -10
        },
      },
      colors: ["#0aa186"],
      fill: {
        gradient: {
          enabled: true,
          opacityFrom: 0.55,
          opacityTo: 0
        }
      },
      legend: {
        position: 'top',
        horizontalAlign: 'right',
        offsetY: -50,
        fontSize: '13px',
        fontFamily: 'Poppins, sans-serif',
        markers: {
          width: 10,
          height: 10,
          strokeWidth: 0,
          strokeColor: '#fff',
          fillColors: undefined,
          radius: 12,
          onClick: undefined,
          offsetX: 0,
          offsetY: 0
        },
        itemMargin: {
          horizontal: 0,
          vertical: 0
        }
      },
      tooltip: {
        theme: 'light',
        marker: {
          show: true,
        },
        x: {
          show: false,
        }
      },
    }







    /*************************** Render Charts Script ************************** /


    

    /* Total Tokens - Render */
    window.onload = function() {
    setTimeout(function(){
        var chart = new ApexCharts(
            document.querySelector("#totalTokensChart"),
            options15
        );
        chart.render();
    }, 500);  // Delay to ensure the container is fully rendered
};



    /* Dashboard To Do List */
    $(document).ready(function(){
      $("#showToDoinput").on('click', function(){
        $("#toDoInputContainer").slideToggle();
      });
    });
   

  } catch(e) {
    console.log(e);
  }
})(jQuery);