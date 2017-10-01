(function(){
  "use strict";

  var Playground = function() {

    if(Playground.instance) {
      return Playground.instance;
    }

    Playground.instance = this;

    this.init();
    };

    window.Playground = Playground;


    Playground.prototype = {
      init: function() {
        this.listenEvents();

        if($("#play_select").val() !== "- Vali -") {
            var select = document.querySelector("#play_select");
            var newname = document.querySelector("#attach-newname"); // Muudab nupu aktiivseks
            var btn = document.querySelector("#attach-btn"); // Muudab nupu aktiivseks
            var editpg = document.querySelector("#edit_playground");
            var add_attach = document.querySelector("#add-attach"); // Muudab nupu aktiivseks

            Playground.instance.fillPlayData();

            if(!isNaN(parseInt(select.value))) {
              newname.disabled = false;
              btn.disabled = false;
              editpg.disabled = false;
            } else {
              newname.disabled = true;
              btn.disabled = true;
              editpg.disabled = true;
              add_attach.disabled = true;

            }

        }

      },

      listenEvents: function() {

        document.querySelector("#save_new_pg").addEventListener("click", function() {
          Playground.instance.editPlayground();
        });

        document.querySelector("#delete_pg").addEventListener("click", function() {
          var c = confirm("Kas oled kindel, et soovid kustutada " + App.instance.currentData.name + ", " + App.instance.currentData.address + "?");
          if(c) {
            Playground.instance.deletePlayground();
          }
        });

        document.querySelector("#area_select").addEventListener("change", function() {
          Playground.instance.updatePlaySelect();
        });
        document.querySelector("#play_select").addEventListener("change", function() {
          var select = document.querySelector("#play_select");
          var newname = document.querySelector("#attach-newname"); // Muudab nupu aktiivseks
          var btn = document.querySelector("#attach-btn"); // Muudab nupu aktiivseks
          var editpg = document.querySelector("#edit_playground");
          var add_attach = document.querySelector("#add-attach"); // Muudab nupu aktiivseks

          Playground.instance.fillPlayData();

          if(!isNaN(parseInt(select.value))) {
            newname.disabled = false;
            btn.disabled = false;
            editpg.disabled = false;
          } else {
            newname.disabled = true;
            btn.disabled = true;
            editpg.disabled = true;
            add_attach.disabled = true;

          }

        });



      },

      updatePlaySelect: function() {
        var area = document.querySelector("#area_select");
        var playground = document.querySelector("#play_select");
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                playground.innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?playarea=" + area.value, true);
        xmlhttp.send();

      },

      editPlayground: function() {

        var id = document.querySelector("#pg_id").value;
        var address = document.querySelector("#pg_address").value;
        var contact = document.querySelector("#pg_contact").value;
        var phone = document.querySelector("#pg_phone").value;
        var attr = document.querySelector("#pg_attr").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
                document.querySelector("#close_edit_pg").click();
                Playground.instance.fillPlayData();
                Playground.instance.updatePlaySelect();
            }
        };
        xmlhttp.open("POST", "../inc/ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("editpg=" + id + "&address=" + address + "&contact=" + contact + "&phone=" + phone + "&attr=" + attr);

      },

      deletePlayground: function() {
        $('#pg_modal').modal('hide');
        document.querySelector("#play_select").querySelectorAll("#play_select option")[0].selected = "selected";
        this.fillPlayData();

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
                document.querySelector("#close_edit_pg").click();
                Playground.instance.updatePlaySelect();
                Playground.instance.fillPlayData();
            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?deletepg=" + App.instance.currentData.id, true);
        xmlhttp.send();

      },

      fillPlayData: function() {
        var playground = document.querySelector("#play_select");
        var address = document.querySelector("#address_value");
        var contact = document.querySelector("#contact_value");
        var phone = document.querySelector("#phone_value");
        var attractions = document.querySelector("#attractions_value");
        var picture_modal = document.querySelector("#playground-modal-img");
        var picture_img = document.querySelector("#playground-img");
        var attach_id = document.querySelector("#attach-id");
        var attach_what = document.querySelector("#attach-what");
        attach_what.value = "playgrounds"; // See määrab kausta nime, ehk tegemist on kategooriaga

        address.innerHTML = "";
        contact.innerHTML = "";
        phone.innerHTML = "";
        attractions.innerHTML = "";
        picture_modal.src = "";
        picture_img.src = "";
        attach_id.value = "";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var data = JSON.parse(this.responseText);

                for(var i = 0; i < data.length; i++) {
                  if(data[i].id.toString() === playground.value) {
                    App.instance.currentData = data[i];
                    App.instance.currentActive = data[i].id;
                    document.querySelector("#pg_id").value = data[i].id;
                    document.querySelector("#pg_address").value = data[i].address;
                    document.querySelector("#pg_contact").value = data[i].contact;
                    document.querySelector("#pg_phone").value = data[i].phone;
                    document.querySelector("#pg_attr").value = data[i].attractions;

                    var attr = data[i].attractions.replace(/\s/g, '');
                    attr = attr.split(",");

                    address.innerHTML = data[i].address;
                    contact.innerHTML = data[i].contact;
                    phone.innerHTML = data[i].phone;

                    for(var j = 0; j < attr.length; j++) {
                      attractions.innerHTML += " <li class='label label-danger'>" + attr[j] + "</li>";
                    }

                    picture_modal.src = "images/playgrounds/" + data[i].id + "-playground" + ".jpg";
                    picture_img.src = "images/playgrounds/" + data[i].id + "-playground" + ".jpg";
                    attach_id.value = data[i].id;


                    Playground.instance.getPlayDocs(data[i].id);
                  } else {
                    Playground.instance.getPlayDocs(0);

                  }
                }

            }
        };
        xmlhttp.open("GET", "../inc/ajax.php?playdata=" + playground.value, true);
        xmlhttp.send();

      },

      getPlayDocs: function(id) {
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
          xmlhttp.open("GET", "../inc/ajax.php?playdocs=" + id, true);
          xmlhttp.send();

        }
      },


    };








}) ();
