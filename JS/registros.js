document.addEventListener('DOMContentLoaded', () => {
const form = document.getElementById('form-registro');
if (form) {
form.addEventListener('submit', function(e) {
e.preventDefault();
let datos = new FormData(this);
fetch('../negocio/Registro.php', {
method: 'POST',
body: datos})
.then(res => res.json())
.then(data => {
if (data.ok) {
alert(data.mensaje);
this.reset();
} else {
 alert('Error: ' + data.error);}})
.catch(err => console.error('Error:', err));
});
}
});