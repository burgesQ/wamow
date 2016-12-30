
$(document).ready(function() {
  // page is ready
  $('#calendar').fullCalendar({
      // calendar properties
  })
});

var globalDate = new Array();
var globalAdd = new Array();
var globalRemove = new Array();


$(function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    function isEventExist (dayDate) //  defiler toutes les dates d'event (start to end chaque) et les comparer avec la date recu en param
     {
      var allEvent = $('#calendar-holder').fullCalendar('clientEvents');
      var i = 0;

      while (i < allEvent.length)
      {
        var dateEvent = moment(allEvent[i].start).format('YYYY-MM-DD');

        if (dayDate == dateEvent)
         {
            return true;
          }
        i++;
      }
      i = 0;
      if (undefined !== globalAdd[0] && globalAdd.length)
      {
        globalAddLen = globalAdd.length;
        while (i < globalAddLen)
        {
          if (dayDate == globalAdd[i])
           {
             return true;
           }
          i++;
        }
      }
      return false;
     }

    function getParent(child)
    {
      var allEvent = $('#calendar-holder').fullCalendar('clientEvents');
      var i = 0;

      while (i < allEvent.length)
      {
        var eventStart = moment(allEvent[i].start).format('YYYY-MM-DD');
        var eventEnd =  moment(allEvent[i].end).format('YYYY-MM-DD');

        if (child > eventStart && child < eventEnd) //si le child est dans l'intervalle du parent
          return allEvent[i];
        i++;
      }
      return null;
    }

    $('#calendar-holder').fullCalendar({
      // enable theme
      theme: true,
      // emphasizes business hours
      businessHours: true,
      // header

      events: Routing.generate('calendar_booking_get'),

      eventRender: function (event, element) {
        var dataStart = moment(event.start).format('YYYY-MM-DD'); // Je recup les dates
        var dataEnd = moment(event.end).subtract(1, 'days').format('YYYY-MM-DD');

        var currentDay = $('#calendar-holder').fullCalendar('getDate').format('YYYY-MM-DD');

        if (dataStart == currentDay)
        {
            $("td[data-date='"+currentDay+"']").removeClass('fc-today');
            $("td[data-date='"+currentDay+"']").removeClass('ui-state-highlight');
        }
        if (event.status == 0)//"BLOCKED")
        {
          $("td[data-date='"+dataStart+"']").addClass('activeDay');

          var i = 0;
          var j = 0;
          while (dataAdd != dataEnd) // addclass du debut à la fin de l'event
            {
              var dataAdd = moment(event.start).add(i, 'days').format('YYYY-MM-DD');

              if (undefined !== globalDate[0] && globalDate.length)
              {
                globalDateLen = globalDate.length;
                while (j < globalDateLen)
                {
                  if (dataAdd != globalDate[j])
                  {
                    $("td[data-date='"+dataAdd+"']").addClass('activeDay');
                  }
                  j++;
                }
            }
            else
            {
              $("td[data-date='"+dataAdd+"']").addClass('activeDay');
            }
            j = 0;
            i++;
           }
         }
       },
      dayRender: function (event, element) {

    },
      eventClick: function(event, element) {

   },
      dayClick: function(date, jsEvent, view) {

        //Je recupere la liste des events a la date cliquée
        var dayEvent = $('#calendar-holder').fullCalendar('clientEvents', function(event) { return +event.start.startOf('day') == +date.startOf('day'); });
        var data = date.format('YYYY-MM-DD');

        var currentDay = $('#calendar-holder').fullCalendar('getDate');
        currentDay = currentDay.format('YYYY-MM-DD');

        var start = data;
        var dayDate = moment(start).format('YYYY-MM-DD');

        var isActive = $("td[data-date='"+data+"']").hasClass('activeDay');

        var indexRemove = globalRemove.indexOf(dayDate);
        var indexAdd = globalAdd.indexOf(dayDate);

        if (data == currentDay)
        {
          if (isActive == true)
          {
            $("td[data-date='"+data+"']").removeClass('activeDay');
            $("td[data-date='"+data+"']").addClass('fc-today');
            $("td[data-date='"+data+"']").addClass('ui-state-highlight');
        //    $("td[data-date='"+data+"']").addClass('toDay');
          }
          else {
        //    $("td[data-date='"+data+"']").removeClass('toDay');
            $("td[data-date='"+data+"']").removeClass('fc-today');
            $("td[data-date='"+data+"']").removeClass('ui-state-highlight');

            $("td[data-date='"+data+"']").addClass('activeDay');
          }

        }
       else if (dayEvent[0])
        {
          if (isActive == true)
          {
              $("td[data-date='"+data+"']").removeClass('activeDay');
              $("td[data-date='"+data+"']").addClass('free');

              if (indexRemove != -1)
              {
                  globalRemove.splice(indexRemove, 1);
              }
          }
          else {
            $("td[data-date='"+data+"']").removeClass('free');
            $("td[data-date='"+data+"']").addClass('activeDay');

             // on add la date dans removeList
            if (indexRemove == -1)
                {
                    globalRemove.push(dayDate);
                }
        // search la date dans le tab
            if (indexAdd != -1)
            {
                globalAdd.splice(indexAdd, 1);
            }
          }
        }
        else
         {
              if (isActive == true)
              {
                $("td[data-date='"+data+"']").removeClass('activeDay');
                $("td[data-date='"+data+"']").addClass('free');
                if (indexRemove != -1)
                {
                    globalRemove.splice(indexRemove, 1);
                }
              }
              else
              {
                $("td[data-date='"+data+"']").removeClass('free');
                $("td[data-date='"+data+"']").addClass('activeDay');

                if (indexRemove == -1)
                    {
                        globalRemove.push(dayDate);
                    }
            // search la date dans le tab
                if (indexAdd != -1)
                {
                    globalAdd.splice(indexAdd, 1);
                }
              }
          }

          var end = moment(start).add(1, 'days').format('YYYY-MM-DD');

          var dayEvent = $('#calendar-holder').fullCalendar('clientEvents', function(event) { return +event.start.startOf('day') == +date.startOf('day'); });

          var index = globalRemove.indexOf(dayDate)
          if (!isEventExist(dayDate) ||  index != -1) //Si Pas d'Event on ajoute un new
          {

          globalRemove.splice(index, 1);
          //moment(start).format('YYYY-MM-DD');
          // end = moment(start).add(1, 'days').format('YYYY-MM-DD');
          globalAdd.push(dayDate);

           $.ajax({
             url: Routing.generate('calendar_booking_add'),
             data: 'start='+ start +'&end='+ end ,
             type: "POST",
             // success: function(json) {
             //   alert('Event Successfully Added to the DataBase !');
             // }
           });
       }
       else if (dayEvent[0])
       {
           if (dayEvent[0].status == 0)
           {
               dayEvent[0].status = 1;
               dayEvent[0].title = "FREE";
               $('#calendar-holder').fullCalendar('updateEvent', dayEvent[0]);

               id = dayEvent[0].id;
               start = data; //moment(start).format('YYYY-MM-DD');
               //     start = moment(start).format('YYYY-MM-DD');
               end = moment(dayEvent[0].end).format('YYYY-MM-DD');
               nextDay = moment(start).add(1, 'days').format('YYYY-MM-DD');

               $.ajax({
                   url:  Routing.generate('calendar_booking_remove'),
                   data: 'start='+ start +'&end='+ end +'&id='+ id +'&nextDay='+ nextDay ,
                   type: "POST",
           // success: function(json) {
           //   alert('Event Successfully Removed from the DataBase !');
           // }
                });
            }
        }
       else
        {
            id = "undefined";
            nextDay = moment(start).add(1, 'days').format('YYYY-MM-DD');

                    $.ajax({
                      url:  Routing.generate('calendar_booking_remove'),
                      data: 'start='+ start +'&end='+ end +'&id='+ id +'&nextDay='+ nextDay ,
                      type: "POST",
                      // success: function(json) {
                      //   alert('Event Successfully Removed from the DataBase !');
                      // }
                    });
       }

      },
      selectable: false,
      selectHelper: true,
      select: function(start, end, allDay) {


      var title = "BLOCKED";
    if (!dayEvent[0]) // Si aucun Event on affiche le nouveau BLOCKED
      {
        $('#calendar-holder').fullCalendar('renderEvent',
        {
          title: title,
          start: start,
          end: end,
          allDay: allDay
        },
        true // make the event "stick"
      );
    }
    //}
 $('#calendar-holder').fullCalendar('unselect');
}
      ,
        header: {
            left: 'prev, next',
            center: 'title',
            right: 'month, basicWeek, basicDay,'
        },
        lazyFetching: true,
        timeFormat: {
            // for agendaWeek and agendaDay
            agenda: 'h:mmt',    // 5:00 - 6:30

            // for all other views
            '': 'h:mmt'         // 7p
        },
        eventSources: [
            {
                url: Routing.generate('fullcalendar_loader'),
                type: 'POST',
                // A way to add custom filters to your event listeners
                data: {
                  },
                error: function() {
                   //alert('There was an error while fetching Google Calendar!');
                }
            }
        ]
    });
});
