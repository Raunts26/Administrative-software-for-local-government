(function(){
  "use strict";

  var Objects = function() {

    if(Objects.instance) {
      return Objects.instance;
    }

    this.currentData = "";

    Objects.instance = this;

    this.init();
    };

    window.Objects = Objects;


    Objects.prototype = {
      init: function() {
        this.listenEvents();
        $( "#object_select" ).change();

        if(localStorage.getItem("object-id")) {
          document.querySelector("#object_select").value = localStorage.getItem("object-id");
          document.querySelector("#maintance_select").value = localStorage.getItem("form-id");
          document.querySelector("#maintance_select_fantom").value = localStorage.getItem("fantom-id");
          document.querySelector("#category_select").value = localStorage.getItem("category-id");
          document.querySelector("#attach-id").value = localStorage.getItem("fill-id");

          this.checkForMaintance();
          this.getDataFromDB(document.querySelector("#attach-id").value);
          this.getMaintanceDocs(document.querySelector("#attach-id").value);

          if(localStorage.getItem("category-id") !== "0") {
            document.querySelector("#maintance_categories").style.display = "block";
          }

          localStorage.removeItem("object-id");
          localStorage.removeItem("form-id");
          localStorage.removeItem("fantom-id");
          localStorage.removeItem("category-id");
          localStorage.removeItem("fill-id");
        }

      },

      listenEvents: function() {

        if(document.querySelector("#add-attach-")) {
          document.querySelector("#add-attach-").addEventListener("click", function() {
            localStorage.setItem('object-id', document.querySelector("#object_select").value);
            localStorage.setItem('form-id', document.querySelector("#maintance_select").value);
            localStorage.setItem('fantom-id', document.querySelector("#maintance_select_fantom").value);
            localStorage.setItem('category-id', document.querySelector("#category_select").value);
            localStorage.setItem('fill-id', document.querySelector("#attach-id").value);

          });
        }

        if(document.querySelector("#maintance_select")) {

          document.querySelector("#maintance_select_fantom").addEventListener("change", function() {
            var fantom = document.querySelector("#maintance_select_fantom");
            var real = document.querySelector("#maintance_select");

            if(fantom.value === "tehniline") {
              document.querySelector("#maintance_categories").style.display = "block";
              document.querySelector("#maintance_archive").innerHTML = "";
              real.value = "0";
            } else {
              document.querySelector("#maintance_categories").style.display = "none";
              document.querySelector("#category_select").value = "0";
              real.value = fantom.value;
            }

          });

          document.querySelector("#category_select").addEventListener("change", function() {
            var fantom = document.querySelector("#category_select");
            var real = document.querySelector("#maintance_select");
            real.value = fantom.value;

            Objects.instance.checkForMaintance();
          });

          document.querySelector("#maintance_select_fantom").addEventListener("change", function() {
            Objects.instance.checkForMaintance();
          });

          document.querySelector("#object_select").addEventListener("change", function() {
            Objects.instance.checkForMaintance();
          });

        }

        if(document.querySelector("#save_new_obj")) {

          document.querySelector("#save_new_obj").addEventListener("click", function() {
            Objects.instance.editObject();
          });

          document.querySelector("#save_new_meta").addEventListener("click", function() {
            Objects.instance.addMeta();
          });


          document.querySelector("#save_obj").addEventListener("click", function() {
            Objects.instance.addObject();
          });

          document.querySelector("#delete_obj").addEventListener("click", function() {
            Objects.instance.deleteObject();
          });

          document.querySelector("#click_image").addEventListener("click", function() {
            document.querySelector("#plan_add_png").click();
          });

          document.querySelector("#click_pdf").addEventListener("click", function() {
            document.querySelector("#plan_add_image").click();
          });

          document.querySelector("#plan_add_image").addEventListener("change", function() {
            Objects.instance.showUploadingPDFs();
          });

          document.querySelector("#plan_add_png").addEventListener("change", function() {
            Objects.instance.showUploadingImages();
          });

          document.querySelector("#floor_plan").addEventListener("change", function() {
            Objects.instance.updateFloorData();
          });

          document.querySelector("#delete_picture").addEventListener("click", function() {
            if(document.querySelector("#floor_plan").value !== "0") {
              Objects.instance.deletePlan();
            }
          });




        }

        if(document.querySelector("#add_new_row_add")) {

          document.querySelector("#add_new_row_add").addEventListener("click", function() {
            Objects.instance.addMaintanceRow();
          });
        }

        if(document.querySelector("#save_maintance_add")) {

          document.querySelector("#save_maintance_add").addEventListener("click", function() {
            Objects.instance.saveMaintance();
          });
        }

        if(document.querySelector("#save_maintance_edit")) {

          document.querySelector("#save_maintance_edit").addEventListener("click", function() {
            Objects.instance.saveMaintanceEditData();
          });

          document.querySelector("#delete_maintance").addEventListener("click", function() {
            var c = confirm("Kas oled kindel, et soovid kustutada antud vormi?");

            if(c) {
              Objects.instance.deleteMaintance();
            }
          });

        }

        document.addEventListener("click", function(e) {
          if(e.target.className === "archive_object" || e.target.parentElement.className === "archive_object") {
            Objects.instance.getDataFromDB(e.target.parentElement.dataset.id);
            Objects.instance.getMaintanceDocs(e.target.parentElement.dataset.id);

          }

        });

        /*
        document.querySelector("#delete_pg").addEventListener("click", function() {
          var c = confirm("Kas oled kindel, et soovid kustutada " + App.instance.currentData.name + ", " + App.instance.currentData.address + "?");
          if(c) {
            Objects.instance.deletePlayground();
          }
        });*/

        if(!document.querySelector("#maintance_select")) {

          $("#object_select").change(function() {
            var select = document.querySelector("#object_select");
            var edit_btn = document.querySelector("#edit_btn");
            var meta_btn = document.querySelector("#meta_btn_add");

            var newname = document.querySelector("#attach-newname"); // Muudab nupu aktiivseks
            var btn = document.querySelector("#attach-btn"); // Muudab nupu aktiivseks
            var editpg = document.querySelector("#edit_playground");
            var add_attach = document.querySelector("#add-attach"); // Muudab nupu aktiivseks

            var delete_picture = document.querySelector("#delete_picture"); // Muudab nupu aktiivseks
            var plan_add_upload = document.querySelector("#plan_add_upload"); // Muudab nupu aktiivseks

            Objects.instance.fillData();

            if(!isNaN(parseInt(select.value)) && parseInt(select.value) !== 0) {
              newname.disabled = false;
              btn.disabled = false;
              edit_btn.disabled = false;
              meta_btn.disabled = false;
              delete_picture.disabled = false;
              plan_add_upload.disabled = false;
            } else {
              newname.disabled = true;
              btn.disabled = true;
              edit_btn.disabled = true;
              meta_btn.disabled = true;
              add_attach.disabled = true;
              delete_picture.disabled = true;
              plan_add_upload.disabled = true;
            }

          });

          /*document.querySelector("#object_select").addEventListener("change", function() {
            var select = document.querySelector("#object_select");
            var edit_btn = document.querySelector("#edit_btn");
            var meta_btn = document.querySelector("#meta_btn_add");

            var newname = document.querySelector("#attach-newname"); // Muudab nupu aktiivseks
            var btn = document.querySelector("#attach-btn"); // Muudab nupu aktiivseks
            var editpg = document.querySelector("#edit_playground");
            var add_attach = document.querySelector("#add-attach"); // Muudab nupu aktiivseks

            Objects.instance.fillData();

            if(!isNaN(parseInt(select.value))) {
              newname.disabled = false;
              btn.disabled = false;
              edit_btn.disabled = false;
              meta_btn.disabled = false;
            } else {
              newname.disabled = true;
              btn.disabled = true;
              edit_btn.disabled = true;
              meta_btn.disabled = true;
              add_attach.disabled = true;

            }

          });*/

        }



      },

      deletePlan: function() {
        var id = document.querySelector("#plan_upload_id").value;
        var name = document.querySelector("#floor_plan").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            document.querySelector("#floor-link").href = "";
            document.querySelector("#floor-img").src = "";
            Objects.instance.getAllPlans();
          }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deleteplan=" + id + "&planname=" + name, true);
        xmlhttp.send();

      },

      getAllPlans: function() {
        var id = document.querySelector("#plan_upload_id").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            var half = "";
            var ready = [];

            for(var i = 0; i < data.length; i++) {
              half = data[i].replace('../plans/objects/' + id + "/","");
              ready.push(half.replace(".jpg", ""));
            }

            Objects.instance.fillPlanSelect(ready);
          }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getplans=" + id, true);
        xmlhttp.send();

      },

      fillPlanSelect: function(data) {
        var select = document.querySelector("#floor_plan");
        select.innerHTML = "";

        var selected = document.createElement("option");
        selected.selected = true;
        selected.value = "0";
        selected.innerHTML = "- Vali -";
        select.appendChild(selected);

        for(var i = 0; i < data.length; i++) {
          var option = document.createElement("option");
          option.value = data[i];
          option.innerHTML = data[i];
          select.appendChild(option);
        }

      },

      updateFloorData: function() {
        var link = document.querySelector("#floor-link");
        var img = document.querySelector("#floor-img");
        var select = document.querySelector("#floor_plan");
        var id = document.querySelector("#plan_upload_id").value;

        link.href = "../plans/objects/" + id + "/" + select.value + ".pdf";
        img.src = "../plans/objects/" + id + "/" + select.value + ".jpg";
      },

      showUploadingPDFs: function() {
        var files = document.querySelector("#plan_add_image").files;
        var names = document.querySelector("#gonna_upload_img");
        names.innerHTML = "";

        for (var i = 0; i < files.length; i++) {
          names.innerHTML += files[i].name;
          if(i !== files.length - 1) {
            names.innerHTML += ", ";
          }
        }

      },

      showUploadingImages: function() {
        var files = document.querySelector("#plan_add_png").files;
        var names = document.querySelector("#gonna_upload_pdf");
        names.innerHTML = "";

        for (var i = 0; i < files.length; i++) {
          names.innerHTML += files[i].name;
          if(i !== files.length - 1) {
            names.innerHTML += ", ";
          }
        }

      },

      addObject: function() {
        var name = document.querySelector("#name_new").value;
        var code = document.querySelector("#code_new").value;
        var year = document.querySelector("#year_new").value;
        var usedfor = document.querySelector("#usedfor_new").value;
        var address = document.querySelector("#address_new").value;
        var contact = document.querySelector("#contact_new").value;
        var email = document.querySelector("#email_new").value;
        var number = document.querySelector("#number_new").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var id = this.responseText;
            console.log(id);
            document.querySelector("#close_obj").click();
            document.querySelector("#object_select").innerHTML += "<option value=" + id + ">" + name + "</option>";

            name = "";
            code = "";
            year = "";
            usedfor = "";
            address = "";
            contact = "";
            email = "";
            number = "";

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?addobject=" + name + "&code=" + code + "&year=" + year + "&usedfor=" + usedfor + "&address=" + address + "&contact=" + contact + "&email=" + email + "&number=" + number, true);
        xmlhttp.send();

      },

      addMeta: function() {
        var a = document.querySelectorAll(".meta_data");
        var id = document.querySelector("#object_meta_id").value;
        var key = "";
        var answer = "";
        var type = "";

        for(var i = 0; i < a.length; i++) {
          if(a[i].value !== "") {
            key += a[i].dataset.name;
            type += a[i].dataset.type;
            answer += a[i].value;
            if(i !== a.length - 1) {
              key += " | ";
              type += " | ";
              answer += " | ";
            }
            /*key.push(a[i].dataset.name);
            type.push(a[i].dataset.type);
            answer.push(a[i].value);*/
          }
        }

        /*console.log(key);
        console.log(answer);
        console.log(type);*/

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector("#close_addmeta").click();
                var axx = JSON.stringify(this.responseText);
                console.log(axx);
                Objects.instance.fillMeta();

                for(var i = 0; i < a.length; i++) {
                  a[i].value = "";
                }
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("addmeta=" + id + "&type=" + type + "&key=" + key + "&answer=" + answer);




      },

      editObject: function() {

        var id = document.querySelector("#object_edit_id").value;
        var name = document.querySelector("#name_edit").value;
        var code = document.querySelector("#code_edit").value;
        var year = document.querySelector("#year_edit").value;
        var usedfor = document.querySelector("#usedfor_edit").value;
        var address = document.querySelector("#address_edit").value;
        var contact = document.querySelector("#contact_edit").value;
        var email = document.querySelector("#email_edit").value;
        var number = document.querySelector("#number_edit").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector("#close_edit_obj").click();

                var select = document.querySelector("#object_select");

                for(var i = 0; i < select.childNodes.length; i++) {
                  if(select.childNodes[i].value === id) {
                    select.childNodes[i].innerHTML = name;
                  }
                }

                Objects.instance.fillData();
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("editobj=" + id + "&name=" + name + "&code=" + code + "&year=" + year + "&usedfor=" + usedfor + "&address=" + address + "&contact=" + contact + "&email=" + email + "&number=" + number);

      },

      deleteObject: function() {
        document.querySelector("#object_select").querySelectorAll("#object_select option")[0].selected = "selected";
        this.fillData();

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
                document.querySelector("#close_edit_obj").click();
                Objects.instance.updateObjectSelect();
                Objects.instance.fillData();
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deleteobject=" + App.instance.currentData.id, true);
        xmlhttp.send();

      },

      updateObjectSelect: function() {

      },

      checkForMaintance: function() {
        if(document.querySelector("#maintance_select").value !== "0" && document.querySelector("#object_select").value !== "0") {
          if(document.querySelector("#only_maintance_data")) {
            Objects.instance.getMaintanceArchive();
            Objects.instance.getMaintanceForm();
            document.querySelector("#doc-list").innerHTML = "";
            //Objects.instance.getDataFromDB();
          } else {
          }
        }
      },

      getMaintanceArchive: function() {
        var object = document.querySelector("#object_select").value;
        var form = document.querySelector("#maintance_select").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);
              //console.log(data);
              Objects.instance.buildMaintanceArchive(data);
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("getmaintancearchive=" + form + "&objectid=" + object);
      },

      buildMaintanceArchive: function(data) {
        var element = document.querySelector("#maintance_archive");
        element.innerHTML = "";

        for(var i = 0; i < data.length; i++) {
          var tr = document.createElement("tr");
          tr.className = "archive_object";
          tr.dataset.id = data[i].id;
          element.appendChild(tr);

          var td1 = document.createElement("td");
          td1.innerHTML = data[i].title;
          tr.appendChild(td1);

          var td2 = document.createElement("td");
          tr.appendChild(td2);

          /*var ifont = document.createElement("i");
          ifont.className = "fa fa-pencil-square-o";
          ifont.dataset.toggle = "modal";
          ifont.dataset.target = "#maintance_edit";
          td2.appendChild(ifont);*/
        }

      },

      fillData: function() {
        var object = document.querySelector("#object_select");
        var code = document.querySelector("#code_answer");
        var year = document.querySelector("#year_answer");
        var usedfor = document.querySelector("#usedfor_answer");
        var address = document.querySelector("#address_answer");
        var contact = document.querySelector("#contact_answer");
        var email = document.querySelector("#email_answer");
        var number = document.querySelector("#number_answer");

        var name_edit = document.querySelector("#name_edit");
        var code_edit = document.querySelector("#code_edit");
        var year_edit = document.querySelector("#year_edit");
        var usedfor_edit = document.querySelector("#usedfor_edit");
        var address_edit = document.querySelector("#address_edit");
        var contact_edit = document.querySelector("#contact_edit");
        var email_edit = document.querySelector("#email_edit");
        var number_edit = document.querySelector("#number_edit");


        var tab1 = document.querySelector("#tab_1");
        var tab2 = document.querySelector("#tab_2");
        var tab3 = document.querySelector("#tab_3");
        var tab4 = document.querySelector("#tab_4");

        var plandatalink = document.querySelector("#floor-link");
        var plandataimg = document.querySelector("#floor-img");


        //var picture_modal = document.querySelector("#modal-img");
        //var picture_img = document.querySelector("#floor-img");
        var attach_id = document.querySelector("#attach-id");
        var attach_what = document.querySelector("#attach-what");
        attach_what.value = "objects"; // See määrab kausta nime, ehk tegemist on kategooriaga

        code.innerHTML = "";
        year.innerHTML = "";
        usedfor.innerHTML = "";
        address.innerHTML = "";
        contact.innerHTML = "";
        email.innerHTML = "";
        number.innerHTML = "";

        name_edit.value = "";
        code_edit.value = "";
        year_edit.value = "";
        usedfor_edit.value = "";
        address_edit.value = "";
        contact_edit.value = "";
        email_edit.value = "";
        number_edit.value = "";

        tab1.innerHTML = "";
        tab2.innerHTML = "";
        tab3.innerHTML = "";

        plandatalink.href = "";
        plandataimg.src = "/images/no-img.jpg";


        //picture_modal.src = "";
        //picture_img.src = "";
        attach_id.value = "";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var data = JSON.parse(this.responseText);

                for(var i = 0; i < data.length; i++) {
                  if(data[i].id.toString() === object.value) {
                    App.instance.currentData = data[i];
                    App.instance.currentActive = data[i].id;

                    code.innerHTML = data[i].code;
                    year.innerHTML = data[i].year;
                    usedfor.innerHTML = data[i].usedfor;
                    address.innerHTML = data[i].address;
                    contact.innerHTML = data[i].contact;
                    email.innerHTML = data[i].email;
                    number.innerHTML = data[i].number;

                    name_edit.value = data[i].name;
                    code_edit.value = data[i].code;
                    year_edit.value = data[i].year;
                    usedfor_edit.value = data[i].usedfor;
                    address_edit.value = data[i].address;
                    contact_edit.value = data[i].contact;
                    email_edit.value = data[i].email;
                    number_edit.value = data[i].number;

                    document.querySelector("#object_edit_id").value = data[i].id;
                    document.querySelector("#object_meta_id").value = data[i].id;

                    document.querySelector("#plan_upload_id").value = data[i].id;


                    //picture_modal.src = "plans/objects/" + data[i].id + "/" + ".jpg";
                    //picture_img.src = "images/playgrounds/" + data[i].id + "-playground" + ".jpg";
                    attach_id.value = data[i].id;



                    Objects.instance.fillMeta();
                    Objects.instance.getAllPlans();
                    Objects.instance.getObjectDocs(data[i].id);

                  } else {
                    Objects.instance.getObjectDocs(0);

                  }
                }

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?objectdata=" + object.value, true);
        xmlhttp.send();

      },

      fillMeta: function() {
        var object = document.querySelector("#object_select");
        var inputs = document.querySelectorAll(".meta_data");
        var tab1 = document.querySelector("#tab_1");
        var tab2 = document.querySelector("#tab_2");
        var tab3 = document.querySelector("#tab_3");		        var tab4 = document.querySelector("#tab_4");

        tab1.innerHTML = "";
        tab2.innerHTML = "";
        tab3.innerHTML = "";		        tab4.innerHTML = "";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var data = JSON.parse(this.responseText);

                for(var i = 0; i < data.length; i++) {
                  if(data[i].object_id.toString() === object.value) {

                    for(var j = 0; j < inputs.length; j++) {
                      if(inputs[j].dataset.name === data[i].meta_key) {
                        inputs[j].value = data[i].meta_answer;
                        break;
                      }
                    }

                    if(data[i].type === 1) {
                      tab1.innerHTML += "<strong>" + data[i].meta_key + "</strong>: " + data[i].meta_answer + "<br>";
                    } else if(data[i].type === 2) {
                      tab2.innerHTML += "<strong>" + data[i].meta_key + "</strong>: " + data[i].meta_answer + "<br>";
                    } else if(data[i].type === 3) {
                      tab3.innerHTML += "<strong>" + data[i].meta_key + "</strong>: " + data[i].meta_answer + "<br>";
                    					 } else if(data[i].type === 4) {                      tab4.innerHTML += "<strong>" + data[i].meta_key + "</strong>: " + data[i].meta_answer + "<br>";                    }

                  } else {

                  }
                }

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?objectmeta=" + object.value, true);
        xmlhttp.send();

      },

      saveMaintance: function() {
        var inputs = document.querySelectorAll(".maintance_data_add");
        var object = document.querySelector("#object_select").value;
        var form = document.querySelector("#maintance_select").value;
        var values = "";

        var d = new Date();
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();

        var date = day + "." + month + "." + year;

        for(var i = 0; i < inputs.length; i++) {
          if(i === inputs.length - 1) {
            values += inputs[i].value;
          } else {
            values += inputs[i].value + " | ";
          }
        }
        //console.log(values);

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              Objects.instance.getDataFromDB(this.responseText);
              document.querySelector("#close_maintance_add").click();
              for(var i = 0; i < inputs.length; i++) {
                inputs[i].value = "";
              }
              Objects.instance.checkForMaintance();

            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("savemaintance=" + form + "&object=" + object + "&title=" + date + "&answer=" + values);

      },

      getDataFromDB: function(id) {

        document.querySelector("#form_edit_btn").disabled = false;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);
              Objects.instance.buildMaintanceForm(data);
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("getmaintance=" + id);
      },

      buildMaintanceForm: function(db_data) {
        var select = document.querySelector("#maintance_select");

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);

              Objects.instance.fillMaintanceData(data, db_data);
              Objects.instance.fillMaintanceEditData(data, db_data);


            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getform=" + select.value, true);
        xmlhttp.send();
      },

      fillMaintanceData: function(data, db_data) {
        document.querySelector("#attach-id").value = db_data[0].id;
        var answers = db_data[0].answer.split(" | ");

        var to = document.querySelector("#form_inner");
        to.innerHTML = "";

        var heads = data[0].heads.split(" | ");
        var rows = data[0].rows.split(" | ");

        var table = document.createElement("table");
        table.id = "maintance_table";
        table.className = "table table-responsive table-striped table-bordered table-condensed";
        to.appendChild(table);

        var thead = document.createElement("thead");
        table.appendChild(thead);

        for(var i = 0; i < heads.length; i++) {
          var th = document.createElement("th");
          th.innerHTML = heads[i];
          thead.appendChild(th);
        }

        var tbody = document.createElement("tbody");
        tbody.id = "maintance_tbody";
        table.appendChild(tbody);

        var tr = "";
        var td = "";
        var td2 = "";
        var input = "";

        for(var j = 0; j < rows.length; j++) {
          if(data[0].name !== 1) {
            tr = document.createElement("tr");
            tbody.appendChild(tr);
            td = document.createElement("td");
            td.innerHTML = rows[j];
            tr.appendChild(td);
            for(var l = 0; l < heads.length - 1; l++) {
              td2 = document.createElement("td");
              td2.className = "maintance_data";
              tr.appendChild(td2);
            }
          } else {

            for(var n = 0; n < answers.length / heads.length; n++) {
              //console.log(n);
              tr = document.createElement("tr");
              tbody.appendChild(tr);
              for(var m = 0; m < heads.length; m++) {
                td2 = document.createElement("td");
                td2.className = "maintance_data";
                tr.appendChild(td2);
              }
            }

          }


        }

        var maintancedata = document.querySelectorAll(".maintance_data");

        for(var k = 0; k < maintancedata.length; k++) {
          maintancedata[k].innerHTML = answers[k];
        }



      },

      fillMaintanceEditData: function(data, db_data) {
        var answers = db_data[0].answer.split(" | ");
        this.currentData = data;

        document.querySelector("#maintance_edit_id").value = db_data[0].id;

        var to = document.querySelector("#edit_inner");
        to.innerHTML = "";

        var heads = data[0].heads.split(" | ");
        var rows = data[0].rows.split(" | ");

        var table = document.createElement("table");
        table.id = "maintance_edit_table";
        table.className = "table table-responsive table-striped table-bordered table-condensed";
        to.appendChild(table);

        var thead = document.createElement("thead");
        table.appendChild(thead);

        for(var i = 0; i < heads.length; i++) {
          var th = document.createElement("th");
          th.innerHTML = heads[i];
          thead.appendChild(th);
        }

        var tbody = document.createElement("tbody");
        tbody.id = "maintance_edit_tbody";
        table.appendChild(tbody);

        var tr = "";
        var td = "";
        var td2 = "";
        var input = "";

        for(var j = 0; j < rows.length; j++) {
          if(data[0].name !== 1) {
            document.querySelector("#add_new_row").style.display = "none";

            tr = document.createElement("tr");
            tbody.appendChild(tr);
            td = document.createElement("td");
            td.innerHTML = rows[j];
            tr.appendChild(td);
            for(var l = 0; l < heads.length - 1; l++) {
              td2 = document.createElement("td");
              tr.appendChild(td2);

              input = document.createElement("textarea");
              input.rows = "auto";
              input.className = "form-control maintance_edit_data";
              td2.appendChild(input);

            }
          } else {
            document.querySelector("#add_new_row").style.display = "block";
            for(var n = 0; n < answers.length / heads.length; n++) {
              //console.log(n);
              tr = document.createElement("tr");
              tbody.appendChild(tr);
              for(var m = 0; m < heads.length; m++) {
                td2 = document.createElement("td");
                //td2.className = "maintance_edit_data";
                tr.appendChild(td2);

                input = document.createElement("textarea");
                input.rows = "auto";
                input.className = "form-control maintance_edit_data";
                td2.appendChild(input);

              }
            }

          }




        }

        var maintancedata = document.querySelectorAll(".maintance_edit_data");

        for(var k = 0; k < maintancedata.length; k++) {
          maintancedata[k].value = answers[k];
        }

        autosize(document.querySelectorAll('textarea'));


      },

      saveMaintanceEditData: function() {
        var id = document.querySelector("#maintance_edit_id").value;
        var inputs = document.querySelectorAll(".maintance_edit_data");
        var values = "";

        for(var i = 0; i < inputs.length; i++) {
          if(i === inputs.length - 1) {
            values += inputs[i].value;
          } else {
            values += inputs[i].value + " | ";
          }
        }

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);
              Objects.instance.getDataFromDB(id);
              document.querySelector("#close_maintance_edit").click();
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("updatemaintance=" + id + "&answer=" + values);


      },

      deleteMaintance: function() {

        document.querySelector("#close_maintance_edit").click();
        var id = document.querySelector("#maintance_edit_id").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);
              document.querySelector("#form_inner").innerHTML = "Vali arhiivist hooldus!";
              Objects.instance.getMaintanceArchive();
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deletemaintance=" + id, true);
        xmlhttp.send();

      },

      getMaintanceForm: function() {
        var select = document.querySelector("#maintance_select");

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);

              var to = document.querySelector("#form_inner_add");
              to.innerHTML = "";

              console.log(data);

              var heads = data[0].heads.split(" | ");
              var rows = data[0].rows.split(" | ");

              var table = document.createElement("table");
              table.id = "maintance_table";
              table.className = "table table-responsive table-striped table-bordered";
              to.appendChild(table);

              var thead = document.createElement("thead");
              table.appendChild(thead);

              for(var i = 0; i < heads.length; i++) {
                var th = document.createElement("th");
                th.innerHTML = heads[i];
                thead.appendChild(th);
              }

              var tbody = document.createElement("tbody");
              tbody.id = "maintance_add_tbody";
              table.appendChild(tbody);

              var tr = "";
              var td = "";
              var td2 = "";
              var input = "";


              if(data[0].name !== 1) {
                document.querySelector("#add_new_row_add").style.display = "none";

                for(var j = 0; j < rows.length; j++) {
                  tr = document.createElement("tr");
                  tbody.appendChild(tr);
                  td = document.createElement("td");
                  td.innerHTML = rows[j];
                  tr.appendChild(td);
                  for(var l = 0; l < heads.length - 1; l++) {
                    td2 = document.createElement("td");
                    tr.appendChild(td2);

                    input = document.createElement("textarea");
                    input.className = "form-control maintance_data_add";
                    input.rows = "1";
                    /*input.dataset.row = rows[j];
                    input.dataset.column = heads[l + 1];*/
                    td2.appendChild(input);

                  }
                }
              } else {
                document.querySelector("#add_new_row_add").style.display = "inline-block";
                Objects.instance.currentData = data;
                Objects.instance.addMaintanceRow();
              }
              autosize(document.querySelectorAll('textarea'));

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?getform=" + select.value, true);
        xmlhttp.send();
      },

      addMaintanceRow: function() {
        var data = this.currentData;
        //console.log(data);
        var heads = data[0].heads.split(" | ");
        var rows = data[0].rows.split(" | ");

        var tr = document.createElement("tr");
        if($('#maintance_edit').hasClass('in')) {
          document.querySelector("#maintance_edit_tbody").appendChild(tr);
        } else {
          document.querySelector("#maintance_add_tbody").appendChild(tr);
        }
        for(var k = 0; k < heads.length; k++) {
          var td = document.createElement("td");
          tr.appendChild(td);
          var input = document.createElement("textarea");
          input.rows = "1";

          if($('#maintance_edit').hasClass('in')) {
            input.className = "form-control maintance_edit_data";
          } else {
            input.className = "form-control maintance_data_add";
          }

          td.appendChild(input);
        }

      },

      getObjectDocs: function(id) {
        var list = document.querySelector("#doc-list");
        App.instance.rememberDocs = [];
        App.instance.currentDocuments = [];
        list.innerHTML = "";
        App.instance.rememberDocLinks = [];

        //console.log(App.instance.rememberDocs);

        if(id !== 0) {

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

              App.instance.currentDocuments = JSON.parse(this.responseText);
              var data = App.instance.currentDocuments;

              for(var i = 0; i < data.length; i++) {
                var exists = false;

                for(var k = 0; k < App.instance.rememberDocs.length; k++) {
                  if(data[i].name === App.instance.rememberDocs[k]) {
                    exists = true;
                  }
                }



                if(exists) {
                  var el = document.querySelectorAll(".doc-element");

                  for(var j = 0; j < el.length; j++) {
                    if(el[j].dataset.name === data[i].name) {
                      el[j].innerHTML += " <a href='" + data[i].link + "'>" + "<img src='../images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
                    }

                    if(App.instance.runDouble) {
                      for(var l = 0; l < el[j].childNodes.length; l++) {
                        var is = false;

                        if(el[j].childNodes[l].nodeType === 1) {
                          //console.log(el[j].childNodes[l].getAttribute("href"));

                          if(el[j].childNodes[l].getAttribute("href") === data[i].link) {

                            for(var a = 0; a < App.instance.rememberDocLinks.length; a++) {
                              if(App.instance.rememberDocLinks[a] === data[i].link) {
                                is = true;
                              }
                            }

                            if(!is) {
                              App.instance.rememberDocLinks.push(data[i].link);
                              //console.log(App.instance.rememberDocLinks);
                              el[j].childNodes[l].remove();


                            }
                          }
                        }
                      }
                    }

                  }

                } else {
                  var name =  document.createElement("span");
                  var li = document.createElement("li");
                  name.dataset.name = data[i].name;
                  name.className += " doc-element";

                  name.innerHTML = "<span class='glyphicon glyphicon-remove rmv-doc' data-id='" + data[i].id + "'></span> " + data[i].name + " <a href='" + data[i].link + "'>" + "<img src='../images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
                  App.instance.rememberDocs.push(data[i].name);

                  li.appendChild(name);
                  document.querySelector("#doc-list").appendChild(li);
                }




              }

            }
          };
          xmlhttp.open("GET", "../inc/ajax.php?objectdocs=" + id, true);
          xmlhttp.send();

        }
      },

      getMaintanceDocs: function(id) {
        var list = document.querySelector("#doc-list");
        /*if(edit) {
          list = document.querySelector("#doc-list");
        } else {
          list = document.querySelector("#doc-list-view");
        }*/
        //console.log(id);

        App.instance.rememberDocs = [];
        App.instance.currentDocuments = [];
        list.innerHTML = "";
        App.instance.rememberDocLinks = [];

        //console.log(App.instance.rememberDocs);

        if(id !== 0) {

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              App.instance.currentDocuments = JSON.parse(this.responseText);
              var data = App.instance.currentDocuments;

              for(var i = 0; i < data.length; i++) {
                var exists = false;

                for(var k = 0; k < App.instance.rememberDocs.length; k++) {
                  if(data[i].name === App.instance.rememberDocs[k]) {
                    exists = true;
                  }
                }



                if(exists) {
                  var el = document.querySelectorAll(".doc-element");

                  for(var j = 0; j < el.length; j++) {
                    if(el[j].dataset.name === data[i].name) {
                      el[j].innerHTML += " <a href='" + data[i].link + "' target='_blank'>" + "<img src='../images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
                    }

                    if(App.instance.runDouble) {
                      for(var l = 0; l < el[j].childNodes.length; l++) {
                        var is = false;

                        if(el[j].childNodes[l].nodeType === 1) {
                          //console.log(el[j].childNodes[l].getAttribute("href"));

                          if(el[j].childNodes[l].getAttribute("href") === data[i].link) {

                            for(var a = 0; a < App.instance.rememberDocLinks.length; a++) {
                              if(App.instance.rememberDocLinks[a] === data[i].link) {
                                is = true;
                              }
                            }

                            if(!is) {
                              App.instance.rememberDocLinks.push(data[i].link);
                              //console.log(App.instance.rememberDocLinks);
                              el[j].childNodes[l].remove();


                            }
                          }
                        }
                      }
                    }

                  }

                } else {
                  var name =  document.createElement("span");
                  var li = document.createElement("li");
                  name.dataset.name = data[i].name;
                  name.className += " doc-element";

                  name.innerHTML = "<span style='color: #ec0202' class='glyphicon glyphicon-remove' data-id='" + data[i].id + "' onclick='Objects.instance.removeDoc(event, " + data[i].id + ")'></span> " + data[i].name + " <a href='" + data[i].link + "' target='_blank'>" + "<img src='../images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
                  App.instance.rememberDocs.push(data[i].name);

                  li.appendChild(name);
                  list.appendChild(li);
                }




              }

            }
          };
          xmlhttp.open("GET", "../inc/ajax.php?maintancedocs=" + id, true);
          xmlhttp.send();

        }
      },

      removeDoc: function(e, id) {
        var c = confirm("Kas oled kindel, et soovid kustutada?");

        if(c) {
          e.target.parentElement.remove();

          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);
            }
          };
          xmlhttp.open("GET", "../inc/ajax.php?removemaintancedoc=" + id, true);
          xmlhttp.send();

        }

      },


    };








}) ();
