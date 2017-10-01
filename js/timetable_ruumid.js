(function(){
  "use strict";

  var Timetable = function() {

    if(Timetable.instance) {
      return Timetable.instance;
    }

    Timetable.instance = this;

    //console.log(this);
    // HTML elements
  this.hours = ["08.00-09.00", "09.00-10.00", "10.00-11.00", "11.00-12.00", "12.00-13.00", "13.00-14.00", "14.00-15.00", "15.00-16.00", "16.00-17.00", "17.00-18.00", "18.00-19.00"];
    this.days = [" ", "Esmaspäev", "Teisipäev", "Kolmapäev", "Neljapäev", "Reede", "Laupäev", "Pühapäev"];
    this.name = document.querySelector('#name');
    this.namevalue = "";
    this.currenttype = "";
    this.exists = false;
    this.click = null;
    this.pin = false;
    this.hour = null;
    this.add = 0;

    this.blockdate = null;
    this.userAmount = 0;
    this.currentAmount = 20;

    //Date variables
    this.firstday = null;
    this.lastday = null;
    this.firstmonth = null;
    this.lastmonth = null;
    this.year = null;
    this.firstMonthDays = null;
    this.lastMonthDays = null;
    this.counter = 0;



    this.init();
    };

    window.Timetable = Timetable;

    Timetable.prototype = {
      init: function() {

        /*if(this.name > 0) {
          this.buildTable(event);
        }*/

        if(localStorage.getItem("name") !== null) {
          this.namevalue = localStorage.getItem("name");
          document.querySelector("#name").value = localStorage.getItem("name");
        }

        this.currentWeek();
        this.bindMouseEvents();

      },

      //Ehitab tabeli
      buildTableComp: function(event) {
        this.counter2 = 0;

        var table = document.createElement("table");
        table.id = "timetable";
		    table.className = "table table-condensed table-striped table-bordered";

        var thead = document.createElement("thead");
        table.appendChild(thead);

        for(var i = 0; i < this.days.length; i++) {
          var th = document.createElement("th");
          var day = document.createTextNode(this.days[i]);
          th.appendChild(day);

          if(i !== 0) {
            var br = document.createElement("br");
            th.appendChild(br);

            var dateSpan = document.createElement("span");
            dateSpan.className = "text-date";
            th.appendChild(dateSpan);

            if(this.firstmonth !== this.lastmonth) {
              var thisday = null;
              var thismonth = null;

              if(this.firstMonthDays < (parseInt(this.firstday)+i-1)) {
                thisday = 0;
                thismonth = this.addZero(parseInt(this.firstmonth)+1);
                this.counter2++;
                var daydate1 = document.createTextNode(this.addZero((parseInt(thisday)+this.counter2)) + "." + thismonth + "." + this.year);
                dateSpan.appendChild(daydate1);
              } else {
                thisday = this.firstday;
                thismonth = this.firstmonth;
                var daydate2 = document.createTextNode(this.addZero((parseInt(thisday)+i-1)) + "." + thismonth + "." + this.year);
                dateSpan.appendChild(daydate2);
              }
            } else {
              var daydate3 = document.createTextNode(this.addZero((parseInt(this.firstday)+i-1)) + "." + this.firstmonth + "." + this.year);
              dateSpan.appendChild(daydate3);
            }



          }


          thead.appendChild(th);

        }

        var tbody = document.createElement("tbody");
        table.appendChild(tbody);

        for(var j = 0; j < this.hours.length; j++) {
          var row = document.createElement("tr");
          this.counter = 0;


          /*if(j % 2 === 0) {
            row.className = "row darker";
          } else {
            row.className = "row";
          }*/

          for(var k = 0; k < this.days.length; k++) {
            var column = document.createElement("td");

            if(k !== 0) {
              var thisday2 = 0;
              var thismonth2 = 0;
              var hourStart = 8 + j;

              column.id = "column_" + j + k;
              column.className = "column-hover";
              column.setAttribute('data-toggle', 'modal');
              column.setAttribute('data-target', '#register');
              //column.setAttribute('data-hour', j + 1);
              column.setAttribute('data-hour', this.addZero(hourStart) + ":" + this.addZero("0") + " - " + this.addZero(hourStart + 1) + ":" + this.addZero("0"));
              //Hakkab 08:00 - 17:00
              //VORM: 08:00 - 09:00


              //column.setAttribute('data-date', this.year + "/" + (parseInt(this.firstmonth) + k - 1) + "/" + (parseInt(this.firstday) + k - 1));

 if(this.firstmonth !== this.lastmonth) {
                if(this.firstMonthDays < (parseInt(this.firstday)+k-1)) {
                  thisday2 = 0;
                  thismonth2 = this.addZero(parseInt(this.firstmonth));
                  this.counter++;
                  column.setAttribute('data-date', this.year + "-" + this.addZero(parseInt(thismonth2) + 1) + "-" + this.addZero(parseInt(thisday2) + this.counter));
                } else {
                  thisday2 = this.firstday;
                  thismonth2 = this.addZero(parseInt(this.firstmonth));
                  column.setAttribute('data-date', this.year + "-" + this.addZero(parseInt(thismonth2)) + "-" + this.addZero(parseInt(thisday2) + k - 1));
                }
              } else {
                column.setAttribute('data-date', this.year + "-" + this.addZero(parseInt(this.firstmonth)) + "-" + this.addZero(parseInt(this.firstday) + k - 1));
              }

              /*<a href="#" id="example"  rel="popover"
              data-content="<div>This <b>is</b> your div content</div>"
              data-html="true" data-original-title="A Title">popover</a>*/

              // column.addEventListener("click", this.blockTime.bind(this));
              column.addEventListener("click", this.listenClick.bind(this));


              /*column.addEventListener("click", this.blockTime.bind(this));
              column.addEventListener("dblclick", this.pinTime.bind(this));*/
            }

            if(k === 0) {
              var hour = document.createTextNode(this.hours[j]);
              column.appendChild(hour);
            }

            row.appendChild(column);

          }

          tbody.appendChild(row);

        }

        document.getElementById("content").appendChild(table);


        this.getBlocked();


      },

      //Hõivab aja, koos ajaxiga, else if ootel ei toimi, pole aega olnud tegeleda.
     blockTime: function() {
        var element = this.click;
        var isOkay = true;
        this.blockdate = element.dataset.date;
        this.hour = element.dataset.hour;

        //console.log("See: " + element.dataset.date);

        if(element.className === "blocked") { // Ja kasutajanimi pole sama
          document.getElementById("answer").innerHTML = "<strong style='color: #FF0000;'>See aeg on juba hõivatud! Palun vali teine aeg.</strong>";
          setInterval(function(){ document.getElementById("answer").innerHTML = ""; }, 3000);
          isOkay = false;
        } else {
          document.getElementById("answer").innerHTML = "<strong style='color: #FF0000;'>Valitud aeg on edukalt salvestatud!</strong>";
          setInterval(function(){ document.getElementById("answer").innerHTML = ""; }, 3000);
        }
        if(isOkay) {


          element.className = "blocked";
          element.innerHTML = this.namevalue;


          var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
             console.log(xhttp.responseText);
            }
          };

          //console.log("NII PLAJU: " + this.userAmount);

          xhttp.open("GET", "../inc/ajax.php?roomblock=1&name=" + this.namevalue + "&room=" + this.currenttype + "&date=" + this.blockdate + "&hour=" + this.hour, true);

          xhttp.send();


        }
        //console.log(element);

      },


      //Kuulab igasuguseid klikke ja muudatusi
      bindMouseEvents: function(event) {

        document.querySelector("#deletetime").addEventListener('click', function() {
          if(Timetable.instance.click.className === "blocked") {
            Timetable.instance.deleteTime(Timetable.instance.click.innerHTML, Timetable.instance.click.dataset.date, Timetable.instance.click.dataset.hour, false);
          }
          Timetable.instance.checkForms();
        });

        document.querySelector('#week').addEventListener('change', function() {
          Timetable.instance.add = parseInt(document.querySelector('#week').value);
          Timetable.instance.currentWeek();
          Timetable.instance.checkForms();
        });

        document.querySelector('#type').addEventListener('change', function() {
            Timetable.instance.currenttype = document.querySelector('#type').value;
            Timetable.instance.checkForms();

        });

          this.name.addEventListener('keyup', function() {
            Timetable.instance.namevalue = document.querySelector('#name').value;
            localStorage.setItem("name", Timetable.instance.namevalue);
            Timetable.instance.checkForms();
            //console.log(Timetable.instance.namevalue);
          });

          document.querySelector("#savetime").addEventListener('click', function() {
            if(Timetable.instance.pin) {
              Timetable.instance.pinTime();

            } else {
              Timetable.instance.blockTime();
            }

          });

      },

      //Kui klikiti mingisuguse kastikese peale siis käivitub see funktsioon ja koos htmli ja bootstrapiga avab modali veel
      listenClick: function(event) {
        this.click = event.target;
        var year = event.target.dataset.date.slice(0,4);
        var month = event.target.dataset.date.slice(5,7);
        var day = event.target.dataset.date.slice(8,11);


          document.querySelector("#myModalLabel").innerHTML = "Ametiautode broneerimine";
          document.querySelector("#blockerTablet").style.display = "none";
          document.querySelector("#blockerAmount").style.display = "none";
          document.querySelector("#currentblockers").style.display = "none";
          document.querySelector("#blockerAmountValue").style.display = "none";
          document.querySelector("#blockerTabletValue").style.display = "none";
          if(event.target.className === "blocked" || event.target.className === "pinned") {
            document.querySelector("#savetime").style.display = "none";
            document.querySelector("#deletetime").style.display = "block";
          } else {
            document.querySelector("#savetime").style.display = "inline-block";
            document.querySelector("#deletetime").style.display = "none";
          }

        document.querySelector("#blockerNameValue").innerHTML = this.namevalue;
        document.querySelector("#blockerDateValue").innerHTML = day + "." + month + "." + year;
        document.querySelector("#blockerHourValue").innerHTML = event.target.dataset.hour;
      },

      //Kontrollib vormide täituvust, kui nõuded täidetud siis jooksutab funktsiooni buildTableComp, mis ehitab ka siis tabeli
      checkForms: function() {
        if(this.exists) {
          timetable.parentNode.removeChild(timetable);
          this.exists = false;
        }
        if(this.namevalue.length > 0 && this.currenttype !== ""){

            this.buildTableComp();
            this.exists = true;


        }


      },

      //
      currentWeek: function() {
        var curr = new Date(); // praegune aeg
        var first = curr.getDate() + this.add - curr.getDay() +1; // First day is the day of the month - the day of the week
        var last = first + 4; // Viimane päev(antud juhul siis reede, +5 oleks laup aga vajab siis tabelis juurde ehitust)

        var month = curr.getMonth() + 1;
        this.year = curr.getFullYear();

        //var firstday = new Date(curr.setDate(first)).toUTCString();
        //var lastday = new Date(curr.setDate(last)).toUTCString();

        //Lõigun ebavajaliku osa maha, mida ma ei kasuta
        this.firstday = new Date((new Date(curr)).setDate(first)).toUTCString().slice(5,7);
        this.lastday = new Date((new Date(curr)).setDate(last)).toUTCString().slice(5,7);
        //console.log("THIS FIRST: " + this.firstday);

        this.firstmonth = new Date((new Date(curr)).setDate(first)).toUTCString().slice(8,11);
        this.lastmonth = new Date((new Date(curr)).setDate(last)).toUTCString().slice(8,11);

        var months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        var nameMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        for(var i = 0; i < months.length; i++) {
          if(this.firstmonth === nameMonths[i]) {
            this.firstmonth = months[i];
          }
          if(this.lastmonth === nameMonths[i]) {
            this.lastmonth = months[i];
          }
        }



        //document.querySelector("#answer").innerHTML = this.firstday + "." + this.firstmonth + "." + this.year + " - " + this.lastday + "." + this.lastmonth + "." + this.year;

        this.firstMonthDays = new Date(this.year, this.firstmonth, 0).getDate();
        this.lastMonthDays = new Date(this.year, this.lastmonth, 0).getDate();

        this.weekOptions();

        //console.log(lastmonth);
      },

      weekOptions: function() {
        var curr = new Date(); // get current date

        //Praegune nädal
        var first1 = curr.getDate() + 0 - curr.getDay() +1; // First day is the day of the month - the day of the week
        var last1 = first1 + 4; // last day is the first day + 6
        var month1 = curr.getMonth() + 1;
        var year1 = curr.getFullYear();
        var firstday1 = new Date((new Date(curr)).setDate(first1)).toUTCString().slice(5,7);
        var lastday1 = new Date((new Date(curr)).setDate(last1)).toUTCString().slice(5,7);
        var firstmonth1 = new Date((new Date(curr)).setDate(first1)).toUTCString().slice(8,11);
        var lastmonth1 = new Date((new Date(curr)).setDate(last1)).toUTCString().slice(8,11);
        var months1 = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        var nameMonths1 = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        for(var k = 0; k < months1.length; k++) {
          if(firstmonth1 === nameMonths1[k]) {
            firstmonth1 = months1[k];
          }
          if(lastmonth1 === nameMonths1[k]) {
            lastmonth1 = months1[k];
          }
        }

        document.querySelector("#this-week").innerHTML = firstday1 + "." + firstmonth1 + "." + year1 + " - " + lastday1 + "." + lastmonth1 + "." + year1;

        //Järgmine nädal
        var first = curr.getDate() + 7 - curr.getDay() +1; // First day is the day of the month - the day of the week
        var last = first + 4; // last day is the first day + 6
        var month = curr.getMonth() + 1;
        var year = curr.getFullYear();
        var firstday = new Date((new Date(curr)).setDate(first)).toUTCString().slice(5,7);
        var lastday = new Date((new Date(curr)).setDate(last)).toUTCString().slice(5,7);
        var firstmonth = new Date((new Date(curr)).setDate(first)).toUTCString().slice(8,11);
        var lastmonth = new Date((new Date(curr)).setDate(last)).toUTCString().slice(8,11);
        var months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        var nameMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        for(var i = 0; i < months.length; i++) {
          if(firstmonth === nameMonths[i]) {
            firstmonth = months[i];
          }
          if(lastmonth === nameMonths[i]) {
            lastmonth = months[i];
          }
        }

        //Ülejärgmine nädal
        var first2 = curr.getDate() + 14 - curr.getDay() +1; // First day is the day of the month - the day of the week
        var last2 = first2 + 4; // last day is the first day + 6
        var month2 = curr.getMonth() + 1;
        var year2 = curr.getFullYear();
        var firstday2 = new Date((new Date(curr)).setDate(first2)).toUTCString().slice(5,7);
        var lastday2 = new Date((new Date(curr)).setDate(last2)).toUTCString().slice(5,7);
        var firstmonth2 = new Date((new Date(curr)).setDate(first2)).toUTCString().slice(8,11);
        var lastmonth2 = new Date((new Date(curr)).setDate(last2)).toUTCString().slice(8,11);
        var months2 = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        var nameMonths2 = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        for(var j = 0; j < months2.length; j++) {
          if(firstmonth2 === nameMonths2[j]) {
            firstmonth2 = months2[j];
          }
          if(lastmonth2 === nameMonths2[j]) {
            lastmonth2 = months2[j];
          }
        }


        document.querySelector("#next-week").innerHTML = firstday + "." + firstmonth + "." + year + " - " + lastday + "." + lastmonth + "." + year;

        document.querySelector("#two-week").innerHTML = firstday2 + "." + firstmonth2 + "." + year2 + " - " + lastday2 + "." + lastmonth2 + "." + year2;
      },

      getBlocked: function() {
        var tabletsum = 0;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (xhttp.readyState == 4 && xhttp.status == 200) {
            var array = [];
            array = JSON.parse(xhttp.responseText);
            var blockName = null;
            var blockDate = null;
            var blockHour = null;
            var blockAmount = null;

              if(array !== []) {
                for(var j = 0; j < array.length; j++) {
                  blockName = array[j].name;
                  blockDate = array[j].date;
                  blockHour = array[j].hour;

                  Timetable.instance.createBlocked(blockName, blockDate, blockHour);

                }
              }

          }
        };

        var start = document.querySelector("#column_01").dataset.date;
        var end = document.querySelector("#column_05").dataset.date;
        //console.log(start + " - " + end);
        xhttp.open("GET", "../inc/ajax.php?blockedrooms=1&room=" + this.currenttype + "&start=" + start + "&end=" + end, true);

        xhttp.send();
      },

      createBlocked: function(name, date, hour, amount) {
        var columns = document.querySelectorAll(".column-hover");
        //console.log(hour + "");

        for(var j = 0; j < columns.length; j++) {
          if(columns[j].dataset.date === date && columns[j].dataset.hour === hour + "") {
            columns[j].className = "blocked";
            columns[j].innerHTML = name;
            //console.log(columns[i]);

          }
        }


        /*element.className = "blocked";
        element.innerHTML = this.namevalue;*/
      },

      deleteTime: function(name, dateOrId, hour, pinned) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (xhttp.readyState == 4 && xhttp.status == 200) {
           //console.log(xhttp.responseText);
          }
        };

        //console.log("NII PLAJU: " + this.userAmount);

        xhttp.open("GET", "../inc/ajax.php?roomdelete=1&date=" + dateOrId + "&room=" + this.currenttype + "&hour=" + hour + "&delete=1", true);




        //xhttp.open("GET", "save.php?name=" + this.namevalue + "&date=" + this.blockdate + "&hour=" + this.hour, true);
        xhttp.send();


      },

      addZero: function(number) {
        if(number < 10) {
          number = "0" + number;
        }
        return number;
      }




    };


    window.onload = function() {
      var app = new Timetable();

    };


}) ();