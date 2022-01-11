var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
var idDoctor = '0123456789';
var result1 = '';
var result2 = '';
var result3 = '';
var result4 = '';
var day = new Date();
var year = day.getFullYear();
for (var i = 0; i < 10; i++) {
    result1 += characters.charAt(Math.floor(Math.random() * characters.length));
    result2 += characters.charAt(Math.floor(Math.random() * characters.length));
    result3 += characters.charAt(Math.floor(Math.random() * characters.length));
}
for (var i = 0; i < 5; i++) {
    result4 += idDoctor.charAt(Math.floor(Math.random() * idDoctor.length));
}
document.getElementById("year").innerHTML = year;
document.getElementById('id').value = `${result1}-${result2}-${result3}`;
document.getElementById('idDoctor').value = result4;