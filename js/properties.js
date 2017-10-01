(function(){
  "use strict";

  var Properties = function() {

    if(Properties.instance) {
      return Properties.instance;
    }

    this.filterActive = false;
    this.filterList = [];
    this.tenantArchive = [];
    this.currentTenant = [];

    Properties.instance = this;


    this.init();
    };

    window.Properties = Properties;


    Properties.prototype = {
      init: function() {
        this.listenEvents();
        $( "#properties_select" ).change();

      },

      listenEvents: function() {

        if(document.querySelector("#go-tenant")) {

          document.querySelector("#go-tenant").addEventListener("click", function() {
            document.querySelector("#go-tenant").style.display = "none";
            document.querySelector("#go-property").style.display = "inline-block";
            document.querySelector("#property-adding").style.display = "none";
            document.querySelector("#tenant-adding").style.display = "block";
          });

          document.querySelector("#go-property").addEventListener("click", function() {
            document.querySelector("#go-tenant").style.display = "inline-block";
            document.querySelector("#go-property").style.display = "none";
            document.querySelector("#property-adding").style.display = "block";
            document.querySelector("#tenant-adding").style.display = "none";
          });

        }

        if(document.querySelector("#save_property")) {
          document.querySelector("#save_property").addEventListener("click", function() {
            Properties.instance.editProperty();
          });
        }

        document.querySelector("#save_tenant").addEventListener("click", function() {
          Properties.instance.doTenant();
        });

        document.querySelector("#clear_rental").addEventListener("click", function() {
          document.querySelector("#clear_rental").style.display = "none";
          Properties.instance.fillPropertyData();
        });

        document.querySelector("#delete_tenant").addEventListener("click", function() {
          var c = confirm("Kas oled kindel, et soovid kustutada antud üürniku?");
          if(c) {
            Properties.instance.deleteTenant();
          }
        });

        document.querySelector("#delete_property").addEventListener("click", function() {
          var c = confirm("Kas oled kindel, et soovid kustutada antud pinna?");
          if(c) {
            Properties.instance.deleteProperty();
          }
        });

        document.querySelector("#area_select").addEventListener("change", function() {
          Properties.instance.updatePropertySelect();
        });

        document.addEventListener("click", function(e) {
          if(e.target.className === "archive_object" || e.target.parentElement.className === "archive_object" || e.target.className === "archive_object active_object" || e.target.parentElement.className === "archive_object active_object") {
            Properties.instance.showArchiveData(e.target.parentElement.dataset.id);
          }

        });


        $("#properties_select").change(function() {
          var select = document.querySelector("#properties_select");
          var newname = document.querySelector("#attach-newname"); // Muudab nupu aktiivseks
          var btn = document.querySelector("#attach-btn"); // Muudab nupu aktiivseks
          var editproperty = document.querySelector("#edit_property");
          var editrental = document.querySelector("#edit_rental");
          var add_attach = document.querySelector("#add-attach"); // Muudab nupu aktiivseks

          Properties.instance.fillPropertyData();

          if(!isNaN(parseInt(select.value))) {
            newname.disabled = false;
            btn.disabled = false;
            editproperty.disabled = false;
            editrental.disabled = false;
          } else {
            newname.disabled = true;
            btn.disabled = true;
            editproperty.disabled = true;
            add_attach.disabled = true;
            editrental.disabled = true;
          }

        });
      },

      deleteTenant: function() {

        document.querySelector("#tenant_name").value = "";
        document.querySelector("#tenant_nid").value = "";
        document.querySelector("#tenant_number").value = "";
        document.querySelector("#tenant_email").value = "";
        document.querySelector("#tenant_real").value = "";
        document.querySelector("#tenant_contract").value = "";
        document.querySelector("#tenant_deadline").value = "";

        var id = document.querySelector("#tenant_real_id").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.querySelector("#tenant_real_id").value = "";
              Properties.instance.getTenant(App.instance.currentActive);
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deletetenant=" + id, true);
        xmlhttp.send();

      },

      deleteProperty: function() {
        $('#myModal').modal('hide');
        document.querySelector("#properties_select").querySelectorAll("#properties_select option")[0].selected = "selected";
        this.fillPropertyData();

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
                document.querySelector("#close_property").click();
                Properties.instance.updatePropertySelect();
                Properties.instance.fillPropertyData();
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deleteproperty=" + App.instance.currentData.id, true);
        xmlhttp.send();

      },

      doTenant: function() {
        var id = document.querySelector("#id_tenant").value;
        var name = document.querySelector("#tenant_name").value;
        var id_rent = document.querySelector("#tenant_nid").value;
        var number = document.querySelector("#tenant_number").value;
        var mail = document.querySelector("#tenant_email").value;
        var real = document.querySelector("#tenant_real").value;
        var contract = document.querySelector("#tenant_contract").value;
        var dhs = document.querySelector("#tenant_dhs").value;
        var deadline = document.querySelector("#tenant_deadline").value;

        if(name.length === 0 && id_rent.length === 0 && number.length === 0) {
          document.querySelector("#close_tenant").click();
          return;
        }


        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.querySelector("#close_tenant").click();
              Properties.instance.getTenant(App.instance.currentActive);
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("dotenant=" + id + "&name=" + name + "&idn=" + id_rent + "&number=" + number + "&email=" + mail + "&real=" + real + "&contract=" + contract + "&dhs=" + dhs + "&deadline=" + deadline);

      },

      getTenant: function(id) {
        var name = document.querySelector("#name_rent");
        var id_rent = document.querySelector("#id_rent");
        var number = document.querySelector("#number_rent");
        var mail = document.querySelector("#mail_rent");
        var real = document.querySelector("#real_rent");
        var contract = document.querySelector("#contract_rent");
        var dhs = document.querySelector("#dhs_rent");
        var deadline = document.querySelector("#deadline_rent");

        name.innerHTML = "";
        id_rent.innerHTML = "";
        number.innerHTML = "";
        mail.innerHTML = "";
        real.innerHTML = "";
        contract.innerHTML = "";
        dhs.innerHTML = "";
        deadline.innerHTML = "";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);

                Properties.instance.currentTenant = data;
                //console.log(Properties.instance.currentTenant);
                Properties.instance.getTenantArchive(id);

                if(data.length > 0) {
                  name.innerHTML = data[0].name;
                  id_rent.innerHTML = data[0].idnumber;
                  number.innerHTML = data[0].number;
                  mail.innerHTML = data[0].email;
                  real.innerHTML = data[0].realhome;
                  contract.innerHTML = data[0].contract;
                  dhs.innerHTML = data[0].dhs;
                  deadline.innerHTML= data[0].deadline;

                  document.querySelector("#tenant_real_id").value = data[0].id;
                  document.querySelector("#tenant_name").value = data[0].name;
                  document.querySelector("#tenant_nid").value = data[0].idnumber;
                  document.querySelector("#tenant_number").value = data[0].number;
                  document.querySelector("#tenant_email").value = data[0].email;
                  document.querySelector("#tenant_real").value = data[0].realhome;
                  document.querySelector("#tenant_contract").value = data[0].contract;
                  document.querySelector("#tenant_dhs").value = data[0].dhs;
                  document.querySelector("#tenant_deadline").value = data[0].deadline;
                } else {
                  document.querySelector("#tenant_real_id").value = "";
                  document.querySelector("#tenant_name").value = "";
                  document.querySelector("#tenant_nid").value = "";
                  document.querySelector("#tenant_number").value = "";
                  document.querySelector("#tenant_email").value = "";
                  document.querySelector("#tenant_real").value = "";
                  document.querySelector("#tenant_contract").value = "";
                  document.querySelector("#tenant_dhs").value = "";
                  document.querySelector("#tenant_deadline").value= "";
                }

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?tenant=" + id, true);
        xmlhttp.send();
      },

      getTenantArchive: function(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              var data = JSON.parse(this.responseText);
              Properties.instance.buildTenantArchive(data);
              Properties.instance.tenantArchive = data;
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?tenantarchive=" + id, true);
        xmlhttp.send();
      },

      buildTenantArchive: function(data) {
        var element = document.querySelector("#tenant_archive");
        element.innerHTML = "";

        //console.log(this.currentTenant);

        if(this.currentTenant.length > 0) {

          var tra = document.createElement("tr");
          tra.className = "archive_object active_object";
          tra.dataset.id = this.currentTenant[0].id;
          element.appendChild(tra);

          var tda1 = document.createElement("td");
          tda1.innerHTML = this.currentTenant[0].idnumber;
          tra.appendChild(tda1);

          var tda2 = document.createElement("td");
          tda2.innerHTML = this.currentTenant[0].deadline;
          tra.appendChild(tda2);

        }

        for(var i = 0; i < data.length; i++) {
          var tr = document.createElement("tr");
          tr.className = "archive_object";
          tr.dataset.id = data[i].id;
          element.appendChild(tr);

          var td1 = document.createElement("td");
          td1.innerHTML = data[i].idnumber;
          tr.appendChild(td1);

          var td2 = document.createElement("td");
          td2.innerHTML = data[i].deadline;
          tr.appendChild(td2);
        }

      },

      showArchiveData: function(id) {
        var data = this.tenantArchive;
        var name = document.querySelector("#name_rent");
        var id_rent = document.querySelector("#id_rent");
        var number = document.querySelector("#number_rent");
        var mail = document.querySelector("#mail_rent");
        var real = document.querySelector("#real_rent");
        var contract = document.querySelector("#contract_rent");
        var dhs = document.querySelector("#dhs_rent");
        var deadline = document.querySelector("#deadline_rent");

        if(this.currentTenant[0] === undefined || this.currentTenant[0].id !== parseInt(id)) {
          document.querySelector("#edit_rental").disabled = true;
          document.querySelector("#clear_rental").style.display = "inline-block";

          for(var i = 0; i < data.length; i++) {
            if(data[i].id === parseInt(id)) {
              name.innerHTML = data[i].name;
              id_rent.innerHTML = data[i].idnumber;
              number.innerHTML = data[i].number;
              mail.innerHTML = data[i].email;
              real.innerHTML = data[i].realhome;
              contract.innerHTML = data[i].contract;
              dhs.innerHTML = data[i].dhs;
              deadline.innerHTML= data[i].deadline;
            }
          }
        } else {
          document.querySelector("#edit_rental").disabled = false;
          document.querySelector("#clear_rental").style.display = "none";

          name.innerHTML = this.currentTenant[0].name;
          id_rent.innerHTML = this.currentTenant[0].idnumber;
          number.innerHTML = this.currentTenant[0].number;
          mail.innerHTML = this.currentTenant[0].email;
          real.innerHTML = this.currentTenant[0].realhome;
          contract.innerHTML = this.currentTenant[0].contract;
          deadline.innerHTML= this.currentTenant[0].deadline;

        }




      },

      updatePropertySelect: function() {
        var area = document.querySelector("#area_select");
        var property = document.querySelector("#properties_select");
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //console.log(this.responseText);
              property.innerHTML = this.responseText;
            }
        };

        xmlhttp.open("GET", "../inc/ajax.php?propertyarea=" + area.value + "&filters=" + this.filterList, true);
        xmlhttp.send();
        //console.log(xmlhttp);

      },

      fillPropertyData: function() {
        var property = document.querySelector("#properties_select");

        var address = document.querySelector("#address_value");
        var rooms = document.querySelector("#rooms_value");
        var space = document.querySelector("#space_value");
        var m2 = document.querySelector("#m2_value");
        var koef = document.querySelector("#koef_value");
        var price = document.querySelector("#price_value");
        var condition = document.querySelector("#condition_value");
        var info = document.querySelector("#info_value");

        var picture_modal = document.querySelector("#property-modal-img");
        var picture_img = document.querySelector("#property-img");
        var attach_id = document.querySelector("#attach-id");
        var attach_what = document.querySelector("#attach-what");
        attach_what.value = "properties"; // See määrab kausta nime, ehk tegemist on kategooriaga

        address.innerHTML = "";
        rooms.innerHTML = "";
        space.innerHTML = "";
        m2.innerHTML = "";
        koef.innerHTML = "";
        price.innerHTML = "";
        condition.innerHTML = "";
        info.innerHTML = "";

        /*picture_modal.src = "";
        picture_img.src = "";*/
        attach_id.value = "";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var data = JSON.parse(this.responseText);

                for(var i = 0; i < data.length; i++) {
                  if(data[i].id.toString() === property.value) {
                    App.instance.currentData = data[i];
                    App.instance.currentActive = data[i].id;
                    //console.log(App.instance.currentActive);
                    data[i].koef = data[i].koef.replace(/,/g, '.');
                    data[i].m2 = data[i].m2.replace(/,/g, '.');
                    data[i].space = data[i].space.replace(/,/g, '.');
                    address.innerHTML = data[i].address;
                    rooms.innerHTML = data[i].rooms;
                    space.innerHTML = data[i].space;
                    m2.innerHTML = data[i].m2;
                    koef.innerHTML = data[i].koef;
                    condition.innerHTML = data[i].condition;
                    price.innerHTML = Math.round((parseFloat(data[i].koef) * parseFloat(data[i].m2) * parseFloat(data[i].space) * 100)) / 100;
                    info.innerHTML = data[i].info;

                    Properties.instance.getTenant(data[i].id);

                    document.querySelector("#id_edit").value = data[i].id;
                    document.querySelector("#address_edit").value = data[i].address;
                    document.querySelector("#rooms_edit").value = data[i].rooms;
                    document.querySelector("#space_edit").value = data[i].space;
                    document.querySelector("#m2_edit").value = data[i].m2;
                    document.querySelector("#koef_edit").value = data[i].koef;
                    document.querySelector("#condition_edit").value = data[i].condition;
                    document.querySelector("#info_edit").value = data[i].info;
                    document.querySelector("#id_tenant").value = data[i].id;
                    document.querySelector("#forsale").checked = data[i].price;


                    //Math.round(num * 100) / 100

                    /*picture_modal.src = "images/properties/" + data[i].id + "-property" + ".jpg";
                    picture_img.src = "images/properties/" + data[i].id + "-property" + ".jpg";*/
                    attach_id.value = data[i].id;


                    Properties.instance.getPropertiesDocs(data[i].id);
                  } else {
                    Properties.instance.getPropertiesDocs(0);

                  }
                }

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?propertydata=" + property.value, true);
        xmlhttp.send();

      },

      editProperty: function() {

        var id = document.querySelector("#id_edit").value;
        var address = document.querySelector("#address_edit").value;
        var rooms = document.querySelector("#rooms_edit").value;
        var space = document.querySelector("#space_edit").value;
        var m2 = document.querySelector("#m2_edit").value;
        var koef = document.querySelector("#koef_edit").value;
        var condition = document.querySelector("#condition_edit").value;
        var info = document.querySelector("#info_edit").value;
        var forsale = document.querySelector("#forsale").checked;

        if(forsale === true) {
          forsale = "on";
        } else {
          forsale = "";
        }

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
                document.querySelector("#close_property").click();
                Properties.instance.fillPropertyData();
                Properties.instance.updatePropertySelect();
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("editproperty=" + id + "&address=" + address + "&rooms=" + rooms + "&space=" + space + "&m2=" + m2 + "&koef=" + koef + "&condition=" + condition + "&info=" + info + "&forsale=" + forsale);

      },

      getPropertiesDocs: function(id) {
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
                      el[j].innerHTML += " <a href='" + data[i].link + "'>" + "<img src='images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
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

                  name.innerHTML = "<span class='glyphicon glyphicon-remove rmv-doc' data-id='" + data[i].id + "'></span> " + data[i].name + " <a href='" + data[i].link + "'>" + "<img src='images/icons/" + data[i].ext + "_icon.png'>" + "</a>";
                  App.instance.rememberDocs.push(data[i].name);

                  li.appendChild(name);
                  document.querySelector("#doc-list").appendChild(li);
                }




              }

            }
          };
          xmlhttp.open("GET", "../inc/ajax.php?propertydocs=" + id, true);
          xmlhttp.send();

        }
      },



    };


}) ();
