document.addEventListener("DOMContentLoaded", function () {
    fetch("dados_dashboard.php")
        .then(response => response.json())
        .then(data => {
            // Contadores para as diferentes opções
            let pendente = 0, emAndamento = 0, transmitido = 0;
            let boletoSim = 0, boletoNao = 0;
            let pagamentoPago = 0, pagamentoPendente = 0;

            // Contar as ocorrências de cada status
            data.forEach(item => {
                // Contagem de transmissões
                if (item.transmissao === "Pendente") pendente++;
                if (item.transmissao === "Em Andamento") emAndamento++;
                if (item.transmissao === "Transmitido") transmitido++;

                // Contagem de boletos enviados
                if (item.boleto_enviado === "Sim") boletoSim++;
                if (item.boleto_enviado === "Não") boletoNao++;

                //Conatgem de pagamentos
                if (item.pagamento === "Pago") pagamentoPago++;
                if (item.pagamento === "Pendente") pagamentoPendente++;
            });

            // Gerando o gráfico
            let ctx = document.getElementById("polarArea").getContext("2d");
            new Chart(ctx, {
                type: "polarArea",
                data: {
                    labels: ["Pendente", "Em Andamento", "Transmitido", "Boleto Enviado (Sim)", "Boleto Enviado (Não)", "Pagamento (Pago)", "Pagamento (Pendente)"],
                    datasets: [{
                        label: "Status",
                        data: [pendente, emAndamento, transmitido, boletoSim, boletoNao, pagamentoPago, pagamentoPendente], // Dados correspondentes
                        backgroundColor: [
                            "rgba(255, 99, 132, 1)", // Cor para "Pendente"
                            "rgba(54, 162, 235, 1)", // Cor para "Em Andamento"
                            "rgba(75, 192, 192, 1)", // Cor para "Transmitido"
                            "rgb(101, 2, 231)", // Cor para "Boleto Enviado (Sim)"
                            "rgba(255, 159, 64, 1)",  // Cor para "Boleto Enviado (Não)"
                            "rgb(0,255,0)", // Cor para "Pagamento (Pago)"
                            "rgb(255,0,0)", // Cor para "Pagamento (Pendente)"
                        ],
                        borderColor: [
                            "rgba(255, 99, 132, 1)",
                            "rgba(54, 162, 235, 1)",
                            "rgba(75, 192, 192, 1)",
                            "rgb(101, 2, 231)",
                            "rgba(255, 159, 64, 1)",
                            "rgb(0,255,0)",
                            "rgb(255,0,0)",
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
