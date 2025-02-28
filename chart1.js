document.addEventListener("DOMContentLoaded", function () {
    fetch("dados_dashboard.php")
        .then(response => response.json())
        .then(data => {
            let tipos = {};
            data.forEach(item => {
                tipos[item.tipo] = (tipos[item.tipo] || 0) + 1;
            });

            let labels = Object.keys(tipos);
            let valores = Object.values(tipos);

            let ctx = document.getElementById("barchart").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Processos por Tipo", 
                        data: valores,
                        backgroundColor: ["#3498db", "#ffca24", "#2ecc71", "#f1c40f"],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,// Incluído para ajustar o tamanho do gráfico
                    maintainAspectRatio: false, // Permite ajustar o tamanho dinamicamente
                    scales: { y: { beginAtZero: true } } 
                }
            });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
});
