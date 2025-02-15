document.addEventListener("DOMContentLoaded", function () {
    fetch("dados_dashboard.php")
        .then(response => response.json())
        .then(data => {
            let status = {};
            data.forEach(item => {
                status[item.conferencia] = (status[item.conferencia] || 0) + 1;
            });

            let labels = Object.keys(status);
            let valores = Object.values(status);

            let ctx = document.getElementById("doughnut").getContext("2d");
            new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Status da Conferência",
                        data: valores,
                        backgroundColor: ["#9b59b6", "#1abc9c", "#e74c3c"]
                    }]
                },
                options: { 
                    responsive: true,// Incluído para ajustar o tamanho do gráfico 
                    maintainAspectRatio: false, // Permite ajustar o tamanho dinamicamente
                }
                
            });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
});
