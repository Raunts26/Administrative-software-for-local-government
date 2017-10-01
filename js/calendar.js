(function(){
  "use strict";

  var Calendar = function() {

    if(Calendar.instance) {
      return Calendar.instance;
    }

    this.similar = [];
    this.filterJob = [];
    this.filterObject = [];
    this.filterMe = false;

    Calendar.instance = this;

    this.init();
    };

    window.Calendar = Calendar;


    Calendar.prototype = {
      init: function() {

        if(document.querySelector("#calendar")) {
          this.listenEvents();
          this.filterEvents();
        } else {
          this.getFiveEvents();
        }

      },

      listenEvents: function() {

        document.querySelector("#filter_btn").addEventListener("click", function () {
            Calendar.instance.filterEvents();
        });

        document.querySelector("#delete_event").addEventListener("click", function () {
            Calendar.instance.deleteEvent();
        });

        document.querySelector("#update_event").addEventListener("click", function () {
            Calendar.instance.updateEvent();
        });

      },

      getFiveEvents: function () {
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                  var data = JSON.parse(this.responseText);
                  Calendar.instance.buildFiveEvents(data);
              }
          };

          xmlhttp.open("GET", "../inc/ajax.php?getfiveevents=1", true);
          xmlhttp.send();
      },

      //<li><span class="text">Rauno testib</span><small class="label label-danger">Registreeritud</small><div class="tools"><i class="fa fa-edit edit-btn" data-id="96" data-toggle="modal" data-target="#editmodal"></i><i class="fa fa-trash-o del-btn" data-id="96"></i></div></li>

      buildFiveEvents: function (data) {
        var to = document.querySelector("#next_events");

        for(var i = 0; i < data.length; i++) {

          var li = document.createElement("li");
          li.dataset.toggle = "tooltip";
          li.dataset.placement = "top";
          li.title = data[i].object_name;
          to.appendChild(li);

          var span = document.createElement("span");
          span.className = "text";
          var newdate = (moment(data[i].start).format("HH:mm") === "00:00") ? moment(data[i].start).format("DD.MM.YYYY") : moment(data[i].start).format("DD.MM.YYYY HH:mm");
          span.innerHTML = newdate + " - " + data[i].text;
          li.appendChild(span);

          /*var small = document.createElement("small");
          small.className = "label label-danger";
          small.innerHTML = data[i].start;
          li.appendChild(small);*/

        }

      },

      filterEvents: function () {
        this.filterJob = [];
        this.filterObject = [];

        if(document.querySelector("#make_checked").checked) {
          this.filterJob = ["0"];
        }

        $(".checked input[name='filter_calendar']").each(function(i){
          console.log($(this).val());
          Calendar.instance.filterJob[i] = $(this).val();
        });

        if($("#filter_object").val() !== undefined) {
          this.filterObject = $("#filter_object").val();
        }

        if(this.filterJob.length > 0 && this.filterObject.length > 0) {
          this.filterMe = true;

        } else if(this.filterJob.length === 0 && this.filterObject.length > 0) {
          this.filterMe = true;
          this.filterJob = null;

        } else if(this.filterJob.length > 0 && this.filterObject.length === 0) {
          this.filterMe = true;
          this.filterObject = null;

        } else {
          this.filterJob = null;
          this.filterObject = null;
          this.filterMe = false;

        }

        $('#calendar').fullCalendar( 'destroy' );
        this.buildCalendar();
      },

      updateEvent: function() {

        var ids = document.querySelector("#id_edit").value;
        var type = document.querySelector("#type_edit").value;
        var start = document.querySelector("#reserv_start_edit").value;
        var end = document.querySelector("#reserv_end_edit").value;
        var text = document.querySelector("#text_edit").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);

            $('#editevent').modal('hide');
            $('#calendar').fullCalendar( 'destroy' );
            Calendar.instance.buildCalendar();

          }
        };
        xmlhttp.open("GET", "../inc/ajax.php?updateevents=" + ids + "&type=" + type + "&start=" + start + "&end=" + end + "&text=" + text, true);
        xmlhttp.send();

      },

      deleteEvent: function() {
        var c = confirm('Oled kindel, et soovid kustutada?');

        if(c) {

          var ids = document.querySelector("#id_edit");

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);

              var array = ids.value.split(",");

              console.log(array);

              for(var i = 0; i < array.length; i++) {
                $('#calendar').fullCalendar( 'removeEvents', array[i]);
              }

              /*$('#calendar').fullCalendar( 'destroy' );
              Calendar.instance.buildCalendar();*/

            }
          };
          xmlhttp.open("GET", "../inc/ajax.php?deleteevents=" + ids.value, true);
          xmlhttp.send();
        }

      },

      getSimilarEvents: function(text, type, start) {

        $.ajax({
          type: 'GET',
          url: "../inc/ajax.php?getsimilarevents=" + text + "&type=" + type + "&start=" + start,
          data: { get_param: 'value' },
          dataType: 'json',
          async: false,
          success: function (data) {
            document.querySelector("#id_edit").value = "";
            var objects = [];

            for(var i = 0; i < data.length; i++) {
              objects.push(data[i].object_id);
              if(i === data.length - 1) {
                document.querySelector("#id_edit").value += data[i].id;
              } else {
                document.querySelector("#id_edit").value += data[i].id + ",";

              }
            }
            Calendar.instance.similar = objects;
          }
        });

      },

      buildCalendar: function() {
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();
        $('#calendar').fullCalendar({
          aspectRatio: 1.5,
          weekends: false,
          //timezone: 'local',
          //defaultView: 'agendaWeek',
          firstDay: 1,
          monthNames: ['Jaanuar', 'Veebruar', 'Märts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'August', 'September', 'Oktoober', 'November', 'Detsember'],
          monthNamesShort: ['Jaanuar', 'Veebruar', 'Märts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'August', 'September', 'Oktoober', 'November', 'Detsember'],
          dayNames: ['Pühapäev', 'Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev'],
          dayNamesShort: ['Pühapäev', 'Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev'],
          minTime: "07:00:00",
          maxTime: "18:00:00",
          noEventsMessage: "Ei leitud ühtegi sündmust",
          dayClick: function(date, jsEvent, view) {
            $('#calendar').fullCalendar( 'changeView', 'agendaDay' );
            $('#calendar').fullCalendar( 'gotoDate', date );
          },
          views: {
               month: {
                 month: 'dddd',
                 week: 'ddd DD.MM',
                 day: 'dddd DD.MM'
               },
               agenda: {
                 columnFormat: 'dddd',
                 slotLabelFormat: 'H:mm',
                 allDaySlot: false,
               }
           },
          /*columnFormat: {
                month: 'dddd',
                week: 'ddd DD.MM',
                day: 'dddd DD.MM'
          },*/
          /*eventStartEditable: false,
          eventDurationEditable: false,*/
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,list'
          },
          buttonText: {
            today: 'täna',
            month: 'kuu',
            week: 'nädal',
            day: 'päev'
          },
          /*views: {
            month: {
              droppable: false
            }
          },*/
          axisFormat: 'H:mm', //,'h(:mm)tt',
          timeFormat: '(HH:mm)', //h:mm{ - h:mm}'
          allDayText: 'Terve päev',
          displayEventEnd: true,
          nextDayThreshold: '00:00:00',

          eventClick: function(calEvent, jsEvent, view) {
            $('#editevent').modal('show');

            Calendar.instance.getSimilarEvents(calEvent.title, calEvent.type, calEvent.start.format("YYYY-MM-DD HH:mm"));
            //console.log(Calendar.instance.similar);

            $("#object_edit").val(Calendar.instance.similar).trigger("change");
            $('#text_edit').val(calEvent.title);
            $("#type_edit").val(calEvent.type).trigger("change");
            $('#reserv_start_edit').val(calEvent.start.format("DD.MM.YYYY HH:mm"));

            if(calEvent.end) {
              $('#reserv_end_edit').val(calEvent.end.format("DD.MM.YYYY HH:mm"));
            }


          },

          events: function(start, end, timezone, callback) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {

                var data = JSON.parse(this.responseText);
                var events = [];

                for(var i = 0; i < data.length; i++) {
                  //console.log(data[i].dow);
                  if(data[i].dow !== null) {
                    events.push({
                      id: data[i].id,
                      title: data[i].text,
                      start: data[i].start,
                      end: data[i].end,
                      backgroundColor: "#e2231a",
                      borderColor: "#e2231a",
                      dow: data[i].dow
                    });
                  } else {
                    events.push({
                      id: data[i].id,
                      object_id: data[i].object_id,
                      type: data[i].type,
                      title: data[i].text,
                      start: data[i].start,
                      end: data[i].end,
                      backgroundColor: "#e2231a",
                      borderColor: "#e2231a",
                    });
                  }

                }

                callback(events);

              }
            };

            if(Calendar.instance.filterMe) {
              xmlhttp.open("GET", "../inc/ajax.php?getcalendarfilter=1&filterjob=" + Calendar.instance.filterJob + "&filterobject=" + Calendar.instance.filterObject, true);
              xmlhttp.send();
            } else {
              xmlhttp.open("GET", "../inc/ajax.php?getcalendar=1", true);
              xmlhttp.send();
            }


          },

          editable: false,
          droppable: false, // this allows things to be dropped onto the calendar !!!
          eventRender: function(event, element) {
            element.tooltip({title: event.title});

          }
        });


      }





    };








}) ();
