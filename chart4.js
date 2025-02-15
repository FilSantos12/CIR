document.addEventListener("DOMContentLoaded", function () {
    fetch("dados_dashboard.php")
        .then(response => response.json())
        .then(data => {
            // Contadores para as diferentes opções
            let pendente = 0, emAndamento = 0, transmitido = 0;
            let boletoSim = 0, boletoNao = 0;

            // Contar as ocorrências de cada status
            data.forEach(item => {
                // Contagem de transmissões
                if (item.transmissao === "Pendente") pendente++;
                if (item.transmissao === "Em Andamento") emAndamento++;
                if (item.transmissao === "Transmitido") transmitido++;

                // Contagem de boletos enviados
                if (item.boleto_enviado === "Sim") boletoSim++;
                if (item.boleto_enviado === "Não") boletoNao++;
            });

            // Gerando o gráfico
            let ctx = document.getElementById("polarArea").getContext("2d");
            new Chart(ctx, {
                type: "polarArea",
                data: {
                    labels: ["Pendente", "Em Andamento", "Transmitido", "Boleto Enviado (Sim)", "Boleto Enviado (Não)"],
                    datasets: [{
                        label: "Status",
                        data: [pendente, emAndamento, transmitido, boletoSim, boletoNao], // Dados correspondentes
                        backgroundColor: [
                            "rgba(255, 99, 132, 0.2)", // Cor para "Pendente"
                            "rgba(54, 162, 235, 0.2)", // Cor para "Em Andamento"
                            "rgba(75, 192, 192, 0.2)", // Cor para "Transmitido"
                            "rgba(153, 102, 255, 0.2)", // Cor para "Boleto Enviado (Sim)"
                            "rgba(255, 159, 64, 0.2)"  // Cor para "Boleto Enviado (Não)"
                        ],
                        borderColor: [
                            "rgba(255, 99, 132, 1)",
                            "rgba(54, 162, 235, 1)",
                            "rgba(75, 192, 192, 1)",
                            "rgba(153, 102, 255, 1)",
                            "rgba(255, 159, 64, 1)"
                        ],
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
