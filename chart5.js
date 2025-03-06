document.addEventListener("DOMContentLoaded", function () {
    fetch("dados_dashboard.php")
        .then(response => response.json())
        .then(data => {
            let totalAbertos = 0;
            let totalTransmitidos = 0;

            data.forEach(item => {
                // Verificando e contando os processos transmitidos
                if (item.transmissao && item.transmissao.trim().toLowerCase() === "transmitido") {
                    totalTransmitidos++; // Conta os processos transmitidos
                } else {
                    totalAbertos++; // Conta os processos abertos
                }
            });

            let ctx = document.getElementById("bar1").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Abertos", "Transmitidos"],
                    datasets: [{
                        label: "Processos",
                        data: [totalAbertos, totalTransmitidos],
                        backgroundColor: ["#ff6384", "#36a2eb"] // Vermelho para abertos, azul para transmitidos
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
});
