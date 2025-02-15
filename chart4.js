document.addEventListener("DOMContentLoaded", function () {
    fetch("dados_dashboard.php")
        .then(response => response.json())
        .then(data => {
            let transmitidos = 0, enviados = 0;

            data.forEach(item => {
                if (item.transmissao === "Sim") transmitidos++;
                if (item.enviada_cliente === "Sim") enviados++;
            });

            let ctx = document.getElementById("line").getContext("2d");
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Transmissão", "Enviada ao Cliente"],
                    datasets: [{
                        label: "Status",
                        data: [transmitidos, enviados],
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false, // Permite ajustar o tamanho dinamicamente
                }
            });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
});
