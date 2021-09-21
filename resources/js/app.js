require("./bootstrap")

window.$swal = require("sweetalert2")
window.$moment = require("moment")
window.$paging = true

options_grafik = { responsive: true, plugins: { legend: false, }, scales: { yAxis: { display: false, }, xAxis: { display: false, } }, elements: { point:{ radius: 0 } } }
