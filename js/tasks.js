(function () {
        "use strict";

        var Tasks = function () {

            if (Tasks.instance) {
                return Tasks.instance;
            }

            this.neededURL = "";
            this.myTable = "";
            this.myTasks = [];
            this.taskPage = 0;
            this.editing = false;
            this.object_edit_id = 0;
            this.filterMe = false;
            this.filterStatus = [];
            this.filterObject = [];
            this.filterUser = "";
            this.responsibleCounter = 1;
            this.responsibleLimit = 5;
            this.responsibleEditCounter = 1;
            this.responsibleEditLimit = 5;

            Tasks.instance = this;

            this.init();
        };

        window.Tasks = Tasks;


        Tasks.prototype = {
            init: function () {
                if (!document.querySelector("#my_tasks")) {
                    this.getData();
                } else {
                    this.countMytasks();
                    this.getMyTasks();
                }

                this.listenEvents();
            },

            listenEvents: function () {
                if (!document.querySelector("#my_tasks")) {

                    document.querySelector("#object_type").addEventListener("change", function () {
                        Tasks.instance.getObjectsSelect();
                    });

                    document.querySelector("#user_search").addEventListener("keyup", function () {
                        if (document.querySelector("#user_search").value.length >= 3) {
                            Tasks.instance.getUsers();
                        }
                    });

                    /*document.querySelector("#user_search_edit").addEventListener("keyup", function () {
                        if (document.querySelector("#user_search_edit").value.length >= 3) {
                            Tasks.instance.getUsers();
                        }
                    });*/

                    document.querySelector("#filter_tasks").addEventListener("click", function () {
                        Tasks.instance.filterTasks();
                    });

                    document.querySelector("#add_user_responsible").addEventListener("click", function () {
                        Tasks.instance.addUserResponsibleInput();
                    });

                    document.querySelector("#add_edit_user_responsible").addEventListener("click", function () {
                        Tasks.instance.addEditUserResponsibleInput();
                    });

                }

                document.querySelector("#object_type_edit").addEventListener("change", function () {
                    Tasks.instance.getObjectsSelect();
                });

                document.querySelector("#save_edit").addEventListener("click", function () {
                    Tasks.instance.saveUpdateData();

                });


                document.addEventListener("click", function (e) {

                    if (!$('#editmodal').hasClass('in')) {
                        Tasks.instance.editing = false;
                    } else {
                        Tasks.instance.editing = true;
                    }

                    if (e.target.parentElement.className === "del-btn") {
                        var c = confirm("Kas oled kindel, et soovid kustutada?");
                        if (c) {
                            Tasks.instance.deleteTask(e.target.parentElement.dataset.id);
                        }
                    }

                    if (e.target.parentElement.className === "edit-btn") {
                        Tasks.instance.getUpdateData(e.target.parentElement.dataset.id);
                    }

                    if (document.querySelector("#my_tasks")) {

                        if (e.target.className === "fa fa-trash-o del-btn") {
                            var k = confirm("Kas oled kindel, et soovid kustutada?");
                            if (k) {
                                Tasks.instance.deleteTask(e.target.dataset.id);
                            }

                        }

                        if (e.target.className === "fa fa-edit edit-btn") {
                            Tasks.instance.getUpdateData(e.target.dataset.id);
                        }

                        if (e.target.className === "page_number") {
                            Tasks.instance.taskPage = e.target.dataset.id - 1;
                            Tasks.instance.getMyTasks();
                        }

                    }

                    if (!document.querySelector("#my_tasks")) {

                        if(e.target.name === "change_responsible_status" || e.target.parentElement.name === "change_responsible_status") {
                          if(e.target.disabled) {
                            return;
                          }
                          Tasks.instance.changeResponsibleStatus(e);
                        }

                        if (e.target.className === "search_element") {
                            Tasks.instance.fillSearchBox(e);
                        } else {
                            if (this.editing) {
                                document.querySelector("#livesearch_edit").innerHTML = "";

                            } else {
                                document.querySelector("#livesearch").innerHTML = "";

                            }
                        }

                    }

                });

            },

            changeResponsibleStatus: function(e) {
              if(e.target.name === "change_responsible_status") {
                if(e.target.dataset.done === "false") {
                  e.target.dataset.done = "true";
                  e.target.style.color = "#00a65a";
                  e.target.childNodes[0].className = "fa fa-check";
                  $(e.target).tooltip('hide').attr('data-original-title', "Määra täitmatuks").tooltip('fixTitle');

                } else {
                  e.target.dataset.done = "false";
                  e.target.style.color = "#d54e21";
                  e.target.childNodes[0].className = "fa fa-times";
                  e.target.dataset.title = "Määra täidetuks";
                  $(e.target).tooltip('hide').attr('data-original-title', "Määra täidetuks").tooltip('fixTitle');
                }
              } else {
                if(e.target.parentElement.dataset.done === "false") {
                  e.target.parentElement.dataset.done = "true";
                  e.target.parentElement.style.color = "#00a65a";
                  e.target.parentElement.childNodes[0].className = "fa fa-check";
                  $(e.target.parentElement).tooltip('hide').attr('data-original-title', "Määra täitmatuks").tooltip('fixTitle');

                } else {
                  e.target.parentElement.dataset.done = "false";
                  e.target.parentElement.style.color = "#d54e21";
                  e.target.parentElement.childNodes[0].className = "fa fa-times";
                  $(e.target.parentElement).tooltip('hide').attr('data-original-title', "Määra täidetuks").tooltip('fixTitle');
                }
              }
            },

            changeResponsibleStatusDatabase: function(statuses) {
              var buttons = $('[name="change_responsible_status"]');
              // 1 1 0 0
              console.log(statuses);
              for(var i = 0; i < buttons.length; i++) {
                if(statuses[i] === "1") {
                  buttons[i].dataset.done = "true";
                  buttons[i].style.color = "#00a65a";
                  buttons[i].childNodes[0].className = "fa fa-check";
                  buttons[i].dataset.title = "Määra täitmatuks";
                  buttons[i].disabled = true;
                  buttons[i].childNodes[0].disabled = true;
                  if(buttons[i + 1]) {
                    buttons[i + 1].disabled = false;
                    buttons[i + 1].childNodes[0].disabled = false;
                  }
                } else {
                  console.log("heara");
                  buttons[i].dataset.done = "false";
                  buttons[i].style.color = "#d54e21";
                  buttons[i].childNodes[0].className = "fa fa-times";
                  buttons[i].dataset.title = "Määra täidetuks";
                  buttons[i].disabled = false;
                }

                if(buttons[i + 1]) {
                  if(buttons[i + 1].dataset.done === "true") {
                    buttons[i].childNodes[0].disabled = true;
                    buttons[i].disabled = true;
                  } else {
                    buttons[i].childNodes[0].disabled = false;
                    buttons[i].disabled = false;
                  }
                }

                if(buttons[i - 1]) {
                  if(buttons[i - 1].dataset.done === "false") {
                    buttons[i].childNodes[0].disabled = true;
                    buttons[i].disabled = true;
                  } else {
                    buttons[i].childNodes[0].disabled = false;
                    buttons[i].disabled = false;
                  }
                }

                if(buttons[i].dataset.done === "true") {
                  buttons[i].childNodes[0].disabled = true;
                  buttons[i].disabled = true;
                  
                }



              }

            },

            addUserResponsibleInput: function() {
              //Kontrollin ega üle limiidi ei ole lisatud vastutajaid
              if(this.responsibleCounter === this.responsibleLimit) {
                return;
              }
              //Kloonin elemendi ja loon selle põhjal uue selecti
              var original = document.querySelectorAll(".responsible_reader")[0];
              var clone = original.cloneNode(true);

              document.querySelector("#add_responsible_list").appendChild(clone);

              $("#person").disabled = true;

              //Teen peidetud nupu, et uue vastutaja lisamine viiks ka nuppu alla poole
              /*var hidden_button = document.createElement("button");
              hidden_button.innerHTML = "<i class='fa fa-plus'></i>";
              hidden_button.className = "btn btn-default";
              hidden_button.type = "button";
              hidden_button.style.visibility = "hidden";

              document.querySelector("#add_button_list").insertBefore(hidden_button, document.querySelector("#add_button_list").childNodes[0]);*/

              //Uuendan select2, et kloonitud selectid saaks ka uhkeks
              $('.responsible_reader').select2();

              //Kontrollin ega üle limiidi ei ole lisatud vastutajaid ja liidan counterisse
              this.responsibleCounter++;

              if(this.responsibleCounter === this.responsibleLimit) {
                document.querySelector("#add_user_responsible").style.display = "none";
              } else if (this.responsibleCounter === this.responsibleLimit - 1) {
                document.querySelector("#push_40down").style.marginBottom = "40px";
              }


            },

            addEditUserResponsibleInput: function() {
              //Kontrollin ega üle limiidi ei ole lisatud vastutajaid
              if(this.responsibleEditCounter === this.responsibleEditLimit) {
                return;
              }
              //Kloonin elemendi ja loon selle põhjal uue selecti
              var original = document.querySelectorAll(".edit_responsible_reader")[0];
              var clone = original.cloneNode(true);
              clone.dataset.order = this.responsibleEditCounter;
              clone.className = "edit_reader";

              document.querySelector("#add_edit_responsible_list").appendChild(clone);

              //staatuse nupu loomine
              var status_button = document.createElement("button");
              status_button.name = "change_responsible_status";
              status_button.innerHTML = "<i class='fa fa-times' disabled></i>";
              status_button.className = "btn btn-default user_adding empty_trigger";
              status_button.style.color = "#d54e21";
              status_button.type = "button";
              status_button.dataset.done = "false";
              status_button.dataset.toggle = "tooltip";
              status_button.dataset.placement = "right";
              status_button.dataset.title = "Määra täidetuks";
              status_button.disabled = true;
              status_button.dataset.order = this.responsibleEditCounter;

              //var already = $('[name="change_responsible_status"]').length;

              document.querySelector("#add_edit_button_list").appendChild(status_button);

              //document.querySelector("#add_edit_button_list").insertAfter(status_button, document.querySelector("#add_edit_button_list").childNodes[already]);

              //Uuendan select2, et kloonitud selectid saaks ka uhkeks
              $('.edit_reader').select2();

              //Kontrollin ega üle limiidi ei ole lisatud vastutajaid ja liidan counterisse
              this.responsibleEditCounter++;

              if(this.responsibleEditCounter === this.responsibleEditLimit) {
                document.querySelector("#add_edit_user_responsible").style.display = "none";
                document.querySelector(".empty_button").style.display = "none";
              } else if (this.responsibleEditCounter === this.responsibleEditLimit - 1) {
                document.querySelector("#push_40down2").style.marginBottom = "40px";
              }


            },

            emptyEditUserResponsibleInput: function() {
              $('.edit_reader').select2('destroy');
              $('.edit_reader').remove();
              $('.empty_trigger').remove();
              this.responsibleEditCounter = 1;
              document.querySelector("#add_edit_user_responsible").style.display = "block";
              document.querySelector(".empty_button").style.display = "block";

            },

            filterTasks: function () {
                this.filterObject = $("#object_filter").val();
                this.filterStatus = $("#status_filter").val();
                this.filterUser = $("input[name='filter_for']:checked").val();
                this.filterMe = true;


                this.myTable.destroy();
                this.getData();
            },

            countMytasks: function () {
                var start = this.taskPage * 5;
                var end = this.taskPage * 5 + 5;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var count = this.responseText;
                        Tasks.instance.buildPages(count);
                    }
                };
                xmlhttp.open("GET", "../inc/ajax.php?countmytasks=1", true);
                xmlhttp.send();
            },

            getMyTasks: function () {
                var start = this.taskPage * 5;
                var end = this.taskPage * 5 + 5;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var data = JSON.parse(this.responseText);
                        Tasks.instance.myTasks = data;
                        Tasks.instance.buildUserTasks();
                    }
                };

                xmlhttp.open("GET", "../inc/ajax.php?getmytasks=1&start=" + start + "&end=" + end, true);
                xmlhttp.send();
            },

            buildUserTasks: function () {
                var list = document.querySelector("#my_tasks");
                list.innerHTML = "";
                var data = Tasks.instance.myTasks;

                for (var i = 0; i < data.length; i++) {

                    var li = document.createElement("li");
                    list.appendChild(li);

                    var span = document.createElement("span");
                    span.className = "text";
                    span.innerHTML = data[i].short;
                    li.appendChild(span);

                    var small = document.createElement("small");
                    if (data[i].status === "Registreeritud") {
                        small.className = "label label-danger";
                    } else {
                        small.className = "label label-info";
                    }
                    small.innerHTML = data[i].status;
                    li.appendChild(small);

                    var div = document.createElement("div");
                    div.className = "tools";
                    li.appendChild(div);

                    var i1 = document.createElement("i");
                    i1.className = "fa fa-edit edit-btn";
                    i1.dataset.id = data[i].id;
                    i1.dataset.toggle = "modal";
                    i1.dataset.target = "#editmodal";
                    div.appendChild(i1);

                    var i2 = document.createElement("i");
                    i2.className = "fa fa-trash-o del-btn";
                    i2.dataset.id = data[i].id;
                    div.appendChild(i2);

                }

            },

            buildPages: function (count) {
                var to = document.querySelector("#pages");
                to.innerHTML = "";
                var pages = 0;
                var times = count;

                for (var i = 0; i < times; i++) {
                    if (count > 0) {
                        count = count - 5;
                        pages++;

                        var li = document.createElement("li");
                        to.appendChild(li);

                        var a = document.createElement("a");
                        a.innerHTML = pages;
                        a.dataset.id = pages;
                        a.href = "#" + pages;
                        a.className = "page_number";
                        li.appendChild(a);

                    }
                }

            },

            fillSearchBox: function (e) {
                if (this.editing) {
                    document.querySelector("#user_search_edit").value = e.target.innerHTML;
                    document.querySelector("#livesearch_edit").innerHTML = "";

                } else {
                    document.querySelector("#user_search").value = e.target.innerHTML;
                    document.querySelector("#livesearch").innerHTML = "";

                }
            },

            getUsers: function () {
                var keyword = null;

                if (this.editing) {
                    keyword = document.querySelector("#user_search_edit").value;

                } else {
                    keyword = document.querySelector("#user_search").value;

                }

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var data = JSON.parse(this.responseText);
                        Tasks.instance.buildUserRecommended(data);
                    }
                };
                xmlhttp.open("GET", "../inc/ajax.php?searchuser=" + keyword, true);
                xmlhttp.send();

            },

            buildUserRecommended: function (data) {
                var result = null;
                if (this.editing) {
                    result = document.querySelector("#livesearch_edit");

                } else {
                    result = document.querySelector("#livesearch");

                }


                result.innerHTML = "";

                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement("tr");
                    tr.style.cursor = "pointer";
                    result.appendChild(tr);

                    var td = document.createElement("td");
                    td.innerHTML = data[i].name;
                    td.className = "search_element";
                    tr.appendChild(td);
                }
                //search.innerHTML = data[0].name;
            },

            getUpdateData: function (id) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        var data = JSON.parse(this.responseText);
                        document.querySelector("#object_type_edit").value = data.object_type;
                        Tasks.instance.fillUpdateData(data);

                        Tasks.instance.object_edit_id = data.real_object_id;
                        Tasks.instance.editing = true;
                        Tasks.instance.getObjectsSelect();


                    }
                };
                xmlhttp.open("GET", "../inc/ajax.php?getdatabyid=" + id, true);
                xmlhttp.send();
            },

            fillUpdateData: function (data) {

                this.emptyEditUserResponsibleInput();

                document.querySelector("#task_id").value = data.id;
                document.querySelector("#location_edit").value = data.location;
                document.querySelector("#short_description_edit").value = data.short;
                document.querySelector("#problem_date_edit").value = moment(data.date).format("DD.MM.YYYY");
                document.querySelector("#priority_edit").value = data.priority;
                document.querySelector("#long_description_edit").value = data.long;
                document.querySelector("#solution_edit").value = data.solution;
                document.querySelector("#problem_adder").innerHTML = data.insertedname;
                //document.querySelector("#user_search_edit").value = data.user;

                data.user = data.user.split(",");
                data.isdone = data.isdone.split(",");

                for(var i = 0; i < data.user.length; i++) {
                  if(i !== data.user.length - 1) {
                    this.addEditUserResponsibleInput();
                  }
                  $("select[data-order='" + i + "']").val(data.user[i]).trigger("change");
                }

                this.changeResponsibleStatusDatabase(data.isdone);

                var newdate = (moment(data.deadline).isValid()) ? moment(data.deadline).format("DD.MM.YYYY") : "Kuupäev puudu";
                document.querySelector("#deadline_edit").value = newdate;

                document.querySelector("#status_edit").value = data.status;
                $('input[value="' + data.source + '"]').iCheck('check');
                $('input[value="' + data.type + '"]').iCheck('check');

                /*if(data.oncalendar) {
                  $('input[name="problem_tocalendar_2"]').iCheck('check');
                  $('input[name="problem_tocalendar_2"]').attr("disabled", true);

                } else {
                  $('input[name="problem_tocalendar_2"]').attr("disabled", false);
                  $('input[name="problem_tocalendar_2"]').iCheck('uncheck');

                }
                $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                    checkboxClass: 'icheckbox_minimal-red',
                    radioClass: 'iradio_square-red',
                });*/

            },

            saveUpdateData: function () {
                var id = document.querySelector("#task_id").value;
                var object_type = document.querySelector("#object_type_edit").value;
                var object_id = document.querySelector("#object_id_edit").value;
                var location = document.querySelector("#location_edit").value;
                var short = document.querySelector("#short_description_edit").value;
                var date = document.querySelector("#problem_date_edit").value;
                var priority = document.querySelector("#priority_edit").value;
                var long = document.querySelector("#long_description_edit").value;
                var solution = document.querySelector("#solution_edit").value;
                var deadline = document.querySelector("#deadline_edit").value;
                var status = document.querySelector("#status_edit").value;
                var type = $('input[name="problem_type"]:checked').val();
                var source = $('input[name="source"]:checked').val();
                var tocalendar = $('input[name="problem_tocalendar_2"]:checked').val();


                var done_buttons = document.querySelectorAll('button[name="change_responsible_status"]');
                var user = document.querySelectorAll("[name='users_edit[]']");
                var dones = [];
                var users = [];

                for(var i = 0; i < user.length; i++) {
                  var allow = false;

                  if(user[i].value !== "") {
                    users.push(user[i].value);
                    allow = true;
                  }

                  if(allow) {
                    if(done_buttons[i].dataset.done === "true") {
                      dones.push(1);
                    } else {
                      dones.push(0);
                    }
                  }

                }

                console.log(dones);
                console.log(users);

                if(tocalendar === undefined) {
                  tocalendar = null;
                }

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        document.querySelector("#close_edit").click();
                        if (document.querySelector("#my_tasks")) {
                            Tasks.instance.countMytasks();
                            Tasks.instance.getMyTasks();
                        } else {
                            Tasks.instance.myTable.destroy();
                            Tasks.instance.getData();
                        }
                    }
                };
                xmlhttp.open("POST", "../inc/ajax.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("savetaskupdate=" + id + "&object_type=" + object_type + "&object_id=" + object_id +
                "&location=" + location + "&type=" + type + "&short=" + short + "&date=" + date +
                "&priority=" + priority + "&source=" + source +
                "&long=" + long + "&solution=" + solution + "&users=" + users +
                "&dones=" + dones + "&deadline=" + deadline + "&status=" + status +
                "&problem_tocalendar=" + tocalendar);
            },

            deleteTask: function (id) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        //console.log(this.responseText);
                        if (!document.querySelector("#my_tasks")) {
                            Tasks.instance.myTable.destroy();
                            Tasks.instance.getData();
                        } else {
                            Tasks.instance.countMytasks();
                            Tasks.instance.getMyTasks();
                        }
                    }
                };
                xmlhttp.open("GET", "../inc/ajax.php?deletetask=" + id, true);
                xmlhttp.send();

            },

            getObjectsSelect: function () {
                var select = null;

                if (this.editing) {
                    select = document.querySelector("#object_type_edit");
                } else {
                    select = document.querySelector("#object_type");
                }

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        //console.log(this.responseText);
                        var data = JSON.parse(this.responseText);
                        Tasks.instance.fillObjectsSelect(data);
                    }
                };
                xmlhttp.open("GET", "../inc/ajax.php?getselectdata=" + select.value, true);
                xmlhttp.send();

            },

            fillObjectsSelect: function (data) {
                var select = null;

                if (this.editing) {
                    select = document.querySelector("#object_id_edit");
                } else {
                    select = document.querySelector("#object_id");
                }

                select.innerHTML = "";

                var selected = document.createElement("option");
                selected.value = "0";
                selected.innerHTML = "- Vali - ";
                select.appendChild(selected);

                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement("option");
                    option.value = data[i].id;
                    option.innerHTML = data[i].name;
                    select.appendChild(option);
                }

                if (this.editing) {
                    select.value = this.object_edit_id;
                }

            },

            getData: function () {
                if (this.filterMe) {
                    if(this.filterObject === undefined) {
                      this.filterObject = null;
                    }
                    this.neededURL = "../inc/ajax.php?getalltasksfilter=1&filteruser=" + this.filterUser + "&filterstatus=" + this.filterStatus + "&filterobject=" + this.filterObject;
                    console.log(this.neededURL);

                } else {
                    this.neededURL = "../inc/ajax.php?getalltasks=1";
                }

                document.querySelector("#table_head").style.display = "table-header-group";

                /*if(this.myTable !== "") {
                 this.myTable.destroy();
                 }*/

                this.myTable = $('#problemtable').DataTable({
                    "language": {
                        "decimal": "",
                        "emptyTable": "Ei leitud vasteid",
                        "info": "Näitan vasteid _START_ kuni _END_. Kokku _TOTAL_ vastet",
                        "infoEmpty": "0 vastet leitud",
                        "infoFiltered": "(Otsitud _MAX_ vaste seast)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Näita _MENU_ vastet",
                        "loadingRecords": "Laen...",
                        "processing": "Töötlen...",
                        "search": "Otsi:",
                        "zeroRecords": "Ei leitud mitte ühtegi vastet",
                        "paginate": {
                            "first": "Esimene",
                            "last": "Viimane",
                            "next": "Järgmine",
                            "previous": "Eelmine"
                        }
                    },
                    "ajax": {
                        "url": Tasks.instance.neededURL,
                        "dataSrc": "",


                    },
                    "columns": [
                        {
                            "render": function (data, type, full, meta) {
                                return '<span class="Tasks_values" data-id="name-' + full.id + '">' + full.object_id + '</span>';
                            }
                        },
                        {
                            "render": function (data, type, full, meta) {
                                return '<span class="Tasks_values" data-id="location-' + full.id + '">' + full.location + '</span>';
                            }
                        },
                        {
                            "render": function (data, type, full, meta) {
                                return '<span class="Tasks_values" data-id="phone-' + full.id + '">' + full.short + '</span>';
                            }
                        },
                        {
                            "render": function (data, type, full, meta) {
                                var newdate = (moment(full.deadline).isValid()) ? moment(full.deadline).format("DD.MM.YYYY") : "Kuupäev puudu";
                                return '<span class="Tasks_values" data-id="info-' + full.id + '">' + newdate + '</span>';
                            }
                        },
                        {
                            "render": function (data, type, full, meta) {
                                return '<span class="Tasks_values" data-id="comment-' + full.id + '">' + full.user_first + " " + full.user_last + '</span>';
                            }
                        },
                        {
                            "render": function (data, type, full, meta) {
                                return '<span class="Tasks_values" data-id="job-' + full.id + '">' + full.priority + '</span>';
                            }
                        },
                        {
                            "render": function (data, type, full, meta) {
                                if (full.status === "Registreeritud") {
                                    return '<span class="Tasks_values" data-id="comment-' + full.id + '"><span class="label label-danger">' + full.status + '</span></span>';
                                } else if (full.status === "Pooleli") {
                                    return '<span class="Tasks_values" data-id="comment-' + full.id + '"><span class="label label-info">' + full.status + '</span></span>';
                                } else {
                                    return '<span class="Tasks_values" data-id="comment-' + full.id + '"><span class="label label-success">' + full.status + '</span></span>';
                                }
                            }
                        },
                        {
                            sortable: false,
                            "render": function (data, type, full, meta) {
                                return '<span class="edit-btn" data-id="' + full.id + '" data-toggle="modal" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span><span class="del-btn" data-id="' + full.id + '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>';
                            }
                        }

                        /*
                         <span class="edit-btn" data-id="23" data-toggle="modal" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span>
                         <span class="del-btn" data-id="23"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span>

                         */
                        /*{ "defaultContent": '<span class="edit-btn" data-toggle="modal" data-id="' + "id" + '" data-target="#editmodal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></span>' }*/
                    ]

                });

                //Näita tulbad kui oled admin
                /*if(App.instance.usergroup === "3" || App.instance.usergroup === "4") {
                 this.myTable.column(3).visible(true); // Paketi tulp
                 this.myTable.column(5).visible(true); // Halda tulp
                 }*/

            }
        };
    })();
