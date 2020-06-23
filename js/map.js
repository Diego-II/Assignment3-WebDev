function getDocsPins() {
    var data_file = "json/comunas.json";
    var xhr = new XMLHttpRequest();
    try {
        xhr = new XMLHttpRequest();
    } catch (e) {
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                alert("Your browser broke!");
                return false;
            }
        }
    }

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
        var container = document.getElementById("container");
        var jsonObj = JSON.parse(xhr.responseText);
        console.log(jsonObj.length);

        javascrypt_array.forEach(element => {
            var ndocs = element['docs'].length;
            var title = element['name'] + " (" + String(ndocs) + ")";
            var marker = L.marker([element['lat'], element['lng']],{title: title}).addTo(mymap);
            var tabla = "<b>"+element['name']+"</b></br>"+
                "<table>"+
                "<th>Nombre</th>"+
                "<th>Twitter</th>"+
                "<th>Email</th>" +
                "<th>Especialidades</th>";
                element['docs'].forEach(medico =>{
                    tabla = tabla+"<tr onclick=goto("+medico['id-medico']+")><td>"+medico['nombre-medico']+"</td>";
                    tabla = tabla+"</td>";
                    tabla = tabla + "<td>"+medico['twitter-medico']+"</td>";
                    tabla = tabla + "<td>"+medico['email-medico']+"</td>";
                    tabla = tabla + "<td>"+ medico['especialidad-medico'].join() +"</td>";
                    tabla = tabla + "</form></td></tr>";
                });
                tabla = tabla+"</table>";
                marker.bindPopup(tabla,{maxWidth: "auto"});
            })
        }
    }
    xhr.open("GET", data_file);
    xhr.send();
}
function goto(id) {
    dir = "ver_medicos.php?page=1&q=" + String(id);
    window.open(dir);
}
