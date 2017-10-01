(function () {
        "use strict";

        var IT_support = function () {

            if (IT_support.instance) {
                return IT_support.instance;
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

            IT_support.instance = this;

            this.init();
        };

        window.IT_support = IT_support;


        IT_support.prototype = {
            init: function () {
                if (!document.querySelector("#my_tasks")) {
                    this.getData();
                } else {
                    this.countMytasks();
                    this.getMyTasks();
                }

                this.listenEvents();
                this.getObjectsSelect();

            },

            listenEvents: function () {

                document.querySelector("#user_search_edit").addEventListener("keyup", function () {
                    if (document.querySelector("#user_search_edit").value.length >= 3) {
                        IT_support.instance.getUsers();
                    }
                });

                document.querySelector("#filter_tasks").addEventListener("click", function () {
                    IT_support.instance.filterTasks();
                });

                document.querySelector("#save_edit").addEventListener("click", function () {
                    IT_support.instance.saveUpdateData();

                });


                document.addEventListener("click", function (e) {

                    if (!$('#editmodal').hasClass('in')) {
                        IT_support.instance.editing = false;
                    } else {
                        IT_support.instance.editing = true;
                    }

                    if (e.target.parentElement.className === "del-btn") {
                        var c = confirm("Kas oled kindel, et soovid kustutada?");
                        if (c) {
                            IT_support.instance.deleteTask(e.target.parentElement.dataset.id);
                        }
                    }

                    if (e.target.parentElement.className === "edit-btn") {
                        IT_support.instance.getUpdateData(e.target.parentElement.dataset.id);
                    }


                });

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
                        IT_support.instance.buildPages(count);
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
                        IT_support.instance.myTasks = data;
                        IT_support.instance.buildUserTasks();
                    }
                };

                xmlhttp.open("GET", "../inc/ajax.php?getmytasks=1&start=" + start + "&end=" + end, true);
                xmlhttp.send();
            },

            buildUserTasks: function () {
                var list = document.querySelector("#my_tasks");
                list.innerHTML = "";
                var data = IT_support.instance.myTasks;

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
                        IT_support.instance.buildUserRecommended(data);
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
                        console.log(JSON.stringify(data));
                        var data = JSON.parse(this.responseText);

                        document.querySelector("#object_type_edit").value = data.object_type;
                        IT_support.instance.fillUpdateData(data);

                        IT_support.instance.object_edit_id = data.real_object_id;
                        IT_support.instance.editing = true;
                        IT_support.instance.getObjectsSelect();


                    }
                };
                xmlhttp.open("GET", "../inc/ajax.php?getitdatabyid=" + id, true);
                xmlhttp.send();
            },

            fillUpdateData: function (data) {

                document.querySelector("#task_id").value = data.id;
                document.querySelector("#location_edit").value = data.location;
                document.querySelector("#tv_id_edit").value = data.tv_id;
                document.querySelector("#short_description_edit").value = data.short;
                document.querySelector("#problem_date_edit").value = moment(data.date).format("DD.MM.YYYY");
                document.querySelector("#priority_edit").value = data.priority;
                document.querySelector("#long_description_edit").value = data.long;
        				document.querySelector("#solution_edit").value = data.solution;
                document.querySelector("#problem_adder").innerHTML = data.insertedname;

                //document.querySelector("#user_search_edit").value = data.user;
                $("#user_search_edit").val(data.user).trigger("change");
                var newdate = (moment(data.deadline).isValid()) ? moment(data.deadline).format("DD.MM.YYYY") : "Kuupäev puudu";
                document.querySelector("#deadline_edit").value = newdate;
                document.querySelector("#status_edit").value = data.status;

            },

            saveUpdateData: function () {
                var id = document.querySelector("#task_id").value;
                var object_type = document.querySelector("#object_type_edit").value;
                var object_id = document.querySelector("#object_id_edit").value;
                var location = document.querySelector("#location_edit").value;
                var tv_id = document.querySelector("#tv_id_edit").value;
                var short = document.querySelector("#short_description_edit").value;
                var date = document.querySelector("#problem_date_edit").value;
                var priority = document.querySelector("#priority_edit").value;
                var long = document.querySelector("#long_description_edit").value;
				var solution = document.querySelector("#solution_edit").value;
                var user = document.querySelector("#user_search_edit").value;
                var deadline = document.querySelector("#deadline_edit").value;
                var status = document.querySelector("#status_edit").value;


                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.querySelector("#close_edit").click();
                        if (document.querySelector("#my_tasks")) {
                            IT_support.instance.countMytasks();
                            IT_support.instance.getMyTasks();
                        } else {
                            IT_support.instance.myTable.destroy();
                            IT_support.instance.getData();
                        }
                    }
                };
                xmlhttp.open("POST", "../inc/ajax.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("saveitsupportupdate=" + id + "&object_type=" + object_type + "&object_id=" + object_id + "&location=" + location + "&tvid=" + tv_id + "&short=" + short + "&date=" + date + "&priority=" + priority + "&long=" + long + "&solution=" + solution + "&user=" + user + "&deadline=" + deadline + "&status=" + status);
            },

            deleteTask: function (id) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        //console.log(this.responseText);
                        if (!document.querySelector("#my_tasks")) {
                            IT_support.instance.myTable.destroy();
                            IT_support.instance.getData();
                        } else {
                            IT_support.instance.countMytasks();
                            IT_support.instance.getMyTasks();
                        }
                    }
                };
                xmlhttp.open("GET", "../inc/ajax.php?deleteitsupport=" + id, true);
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
                        IT_support.instance.fillObjectsSelect(data);
                    }
                };
                xmlhttp.open("GET", "../inc/ajax.php?getselectdata=" + 1, true);
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
                    this.neededURL = "../inc/ajax.php?getallitsupportfilter=1&filteruser=" + this.filterUser + "&filterstatus=" + this.filterStatus + "&filterobject=" + this.filterObject;
                    console.log(this.neededURL);

                } else {
                    this.neededURL = "../inc/ajax.php?getallitsupport=1";
                }

                document.querySelector("#table_head").style.display = "table-header-group";

                /*if(this.myTable !== "") {
                 this.myTable.destroy();
                 }*/

                this.myTable = $('#itsupporttable').DataTable({
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
                        "url": IT_support.instance.neededURL,
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

                this.myTable.on( 'xhr', function ( e, settings, json ) {
                    console.log( 'Ajax event occurred. Returned data: ', json );
                } );



                //Näita tulbad kui oled admin
                /*if(App.instance.usergroup === "3" || App.instance.usergroup === "4") {
                 this.myTable.column(3).visible(true); // Paketi tulp
                 this.myTable.column(5).visible(true); // Halda tulp
                 }*/

            }
        };
    })();
