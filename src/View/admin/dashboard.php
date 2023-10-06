<?php

require_once dirname(__DIR__, 2) . '/Resource/dataview/dashboard_dataview.php';
?>
<!DOCTYPE html>
<html>
<?php include_once PATH_URL . '/Template/_includes/_head.php' ?>
<style>
    .card {
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        margin: 10px;
        padding: 10px;
    }

    .card-header {
        background-color: #f7f7f7;
        border-bottom: 1px solid #ccc;
        padding: 10px;
    }

    .chart-container {
        max-width: 100%;
        height: auto;
    }

    .card-primary {
        background-color: #2C6AA0;
        color: #fff;
    }

    .card-primary .card-header {
        background-color: #6495ED;
        color: #fff;
    }

    .card-primary .card-body {
        background-color: white;
        color: #000;
    }

    .card-primary .card-footer {
        background-color: #6495ED;
        color: #fff;
    }
</style>


</head>

<body class="skin-1">
    <?php include_once PATH_URL . '/Template/_includes/_topo.php' ?>
    <!-- topo-->


    <!--inicio do conteudo principal-->
    <div class="main-container ace-save-state" id="main-container">
        <script type="text/javascript">
            try {
                ace.settings.loadState('main-container')
            } catch (e) {}
        </script>

        <?php include_once PATH_URL . '/Template/_includes/_menu.php' ?>

        <div class="main-content">
            <div class="main-content-inner">
                <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="#">Dashboard</a>
                        </li>

                    </ul><!-- /.breadcrumb -->


                </div>
                <!-- conteudo da pagina -->
                <div class="page-content">
                    <div class="page-header">
                        <h1>
                            Dashboard das O.S
                            <small>
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                Aqui são exibidos os dados e estatísticas das ordens de serviços
                            </small>
                        </h1>
                    </div><!-- /.page-header -->


                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <span class="btn btn-app btn-sm btn-primary no-hover">
                                <!-- conteúdo da primeira div -->
                                <i class="fa fa-ticket fa-2x card-icon float-left mr-10"></i>
                                <span id="total_chamados" name="total_chamados" class="line-height-1 bigger-170"></span>

                                <br>
                                <span class="line-height-1 smaller-90"> Total O.S </span>

                            </span>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <span class="btn btn-app btn-sm btn-danger no-hover">
                                <i class="fa fa-ticket fa-2x card-icon float-left mr-10"></i>
                                <span id="aguardando" class="line-height-1 bigger-170"></span>

                                <br>
                                <span class="line-height-1 smaller-90"> O.S aguardando </span>
                            </span>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <span class="btn btn-app btn-sm btn-yellow no-hover">
                                <i class="fa fa-ticket fa-2x card-icon float-left mr-10"></i>
                                <span id="em_atendimento" class="line-height-1 bigger-170"></span>

                                <br>
                                <span class="line-height-1 smaller-90"> O.S em atendimento </span>
                            </span>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <span class="btn btn-app btn-sm btn-success no-hover">
                                <i class="fa fa-ticket fa-2x card-icon float-left mr-10"></i>
                                <span id="concluidos" class="line-height-1 bigger-170"></span>

                                <br>
                                <span class="line-height-1 smaller-90"> O.S concluídas </span>
                            </span>
                        </div>
                    </div>

                    <div class="space-12"></div>
                    <div class="space-20"></div>
                    <!-- <div class="row">
                    <div class="col-4">
                        <div class="chart-container">

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Meu gráfico de pizza</h5>
                                </div>
                                <div class="card-body">
                                    <canvas style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 709px;" width="709" height="250" id="myPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="chart-container">

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Meu gráfico de pizza</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="chart-container">

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Meu gráfico de pizza</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="myBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="widget-box">
                                <div class="widget-header widget-header-flat widget-header-small card-primary">
                                    <h5 class="widget-title">
                                        <i class="ace-icon fa fa-signal"></i>
                                        Quantidade por status
                                    </h5>


                                </div>

                                <div class="widget-body" style="margin-bottom: 20px;">
                                    <div class="widget-main">
                                        <canvas id="chart_chamados_status" style="min-height: 250px; height: 250px; max-height: 350px; max-width: 100%; display: block; width: 709px;" width="709" height="250" class="chartjs-render-monitor"></canvas>
                                        <div class="hr hr8 hr-double"></div>

                                        <div class="clearfix">
                                            <div class="grid12">
                                                <span class="grey">
                                                    <i class="ace-icon fa fa-ticket fa-2x blue"></i>
                                                    &nbsp; Quantidade total de ordens de serviços:
                                                </span>
                                                <h4 id="qtd_chamado_por_status" class="bigger pull-right"></h4>
                                            </div>

                                        </div>
                                    </div><!-- /.widget-main -->
                                </div><!-- /.widget-body -->
                            </div><!-- /.widget-box -->
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="widget-box">
                                <div class="widget-header widget-header-flat widget-header-small card-primary">
                                    <h5 class="widget-title">
                                        <i class="ace-icon fa fa-signal"></i>
                                        Estatística das ordens de serviços
                                    </h5>


                                </div>

                                <div class="widget-body" style="margin-bottom: 20px;">
                                    <div class="widget-main">
                                        <canvas id="chamado_por_responsavel" style="min-height: 250px; height: 250px; max-height: 350px; max-width: 100%; display: block; width: 709px;" width="709" height="250" class="chartjs-render-monitor"></canvas>
                                        <div class="hr hr8 hr-double"></div>

                                        <div class="clearfix">
                                            <div class="grid12">
                                                <span class="grey">
                                                    <i class="ace-icon fa fa-ticket fa-2x blue"></i>
                                                    &nbsp; Quantidade total de ordens de serviços:
                                                </span>
                                                <h4 id="qtd_chamado_por_responsável" class="bigger pull-right"></h4>
                                            </div>

                                        </div>
                                    </div><!-- /.widget-main -->
                                </div><!-- /.widget-body -->
                            </div><!-- /.widget-box -->
                        </div>







                        <div class="col-sm-6 col-xs-12">
                            <div class="widget-box">
                                <div class="widget-header widget-header-flat widget-header-small card-primary">
                                    <h5 class="widget-title">
                                        <i class="ace-icon fa fa-signal"></i>
                                        Quantidade por setor
                                    </h5>


                                </div>

                                <div class="widget-body" style="margin-bottom: 20px;">
                                    <div class="widget-main">
                                        <canvas id="chamado_por_setor" style="min-height: 250px; height: 250px; max-height: 350px; max-width: 100%; display: block; width: 709px;" width="709" height="250" class="chartjs-render-monitor"></canvas>
                                        <div class="hr hr8 hr-double"></div>

                                        <div class="clearfix">
                                            <div class="grid12">
                                                <span class="grey">
                                                    <i class="ace-icon fa fa-ticket fa-2x blue"></i>
                                                    &nbsp; Quantidade total de ordens de serviços:
                                                </span>
                                                <h4 id="qtd_chamado_por_setor" class="bigger pull-right"></h4>
                                            </div>

                                        </div>
                                    </div><!-- /.widget-main -->
                                </div><!-- /.widget-body -->
                            </div><!-- /.widget-box -->
                        </div>





                      <!--  <div class="col-sm-12">
                            <div class="widget-box transparent">
                               

                                <div class="widget-body" style="display: block;">
                                    <div class="widget-main no-padding">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thin-border-bottom">
                                                <tr>
                                                    <th>
                                                        <i class="ace-icon fa fa-caret-right blue"></i>N° Nota Fiscal
                                                    </th>

                                                    <th>
                                                        <i class="ace-icon fa fa-caret-right blue"></i>Data de Lançamento
                                                    </th>

                                                    <th class="hidden-480">
                                                        <i class="ace-icon fa fa-caret-right blue"></i>Status
                                                    </th>

                                                    <th class="hidden-480">
                                                        <i class="ace-icon fa fa-caret-right blue"></i>Valor Total
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                if (isset($dados) && !empty($dados)) :
                                                    //var_dump($dados);
                                                    foreach ($dados as $dado) :
                                                ?>
                                                        <tr>
                                                            <?php if (isset($dado['numero_nf'])) : ?>
                                                                <td><?= $dado['numero_nf'] ?></td>
                                                            <?php else : ?>
                                                                <td>Número NF não definido</td>
                                                            <?php endif; ?>

                                                            <?php if (isset($dado['data_atendimento'])) : ?>
                                                                <td><?= $dado['data_atendimento'] ?></td>
                                                            <?php else : ?>
                                                                <td>Data atendimento não definida</td>
                                                            <?php endif; ?>

                                                            <?php if (isset($dado['status'])) : ?>
                                                                <td><?= $dado['status'] ?></td>
                                                            <?php else : ?>
                                                                <td>Número NF não definido</td>
                                                            <?php endif; ?>
                                                            </td>

                                                            <?php if (isset($dado['valor_total'])) : ?>
                                                                <td><?= $dado['valor_total'] ?></td>
                                                            <?php else : ?>
                                                                <td>Número NF não definido</td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php
                                                    endforeach;
                                                else :
                                                    ?>
                                                    <tr>
                                                        <td colspan="4">Os dados de chamados não foram recebidos.</td>
                                                    </tr>
                                                <?php
                                                endif;
                                                ?>

                                            </tbody>


                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> -->























                    </div>



                </div><!-- /.final do conteudo da pagina -->
            </div>
        </div><!-- /.main-content -->

        <?php include_once PATH_URL . '/Template/_includes/_footer.php' ?>


    </div><!-- /.final do conteudo Princial -->







    <?php include_once PATH_URL . '/Template/_includes/_scripts.php' ?>
    <script src="../../Resource/ajax/dashboard-ajx.js"></script>
    <script>
        BuscarChamadosPorColaborador();
        BuscarChamadosPorStatus();
        
        
        
        
        
        
        
function BuscarChamadosPorColaborador() {
  $.ajax({
    url: BASE_URL_AJAX("dashboard_dataview"),
    method: 'GET',
    dataType: 'json',
    data: { acao: 'requisicao' },
    success: function (data) {
      var labels = [];
      var valores = [];
      var totalGeral = [];
      $.each(data, function (index, item) {
        labels.push(item.nome_funcionario);
        valores.push(item.total);
        totalGeral = item.total;
      });
      $("#qtd_chamado_por_responsável").html(totalGeral);
      $("#qtd_chamado_por_periodo").html(totalGeral);
      var ctx = document.getElementById('chamado_por_responsavel').getContext('2d');
      var meuGrafico = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            data: valores,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          plugins: {
            legend: {
              display: false, // Exibir legenda
              position: 'bottom' // Posição da legenda (pode ser 'top', 'bottom', 'left' ou 'right')
            },
            datalabels: {
              formatter: function (value, context) {
                return value + " (" + context.chart.data.labels[context.dataIndex] + ")";
              },
              color: "#fff"
            }
          }
        }
      });
    }
  });
}



function BuscarChamadosPorSetor() {
  $.ajax({
    url: BASE_URL_AJAX("dashboard_dataview"),
    method: 'GET',
    dataType: 'json',
    data: { acao: 'chamado_por_setor' },
    success: function (dados) {
      console.log(dados);

      var labels = [];
      var valores = [];

      for (var i = 0; i < dados.length; i++) {
        var setor = dados[i];

        labels.push(setor.nome_setor);
        valores.push(setor.total);
      }
      $("#qtd_chamado_por_setor").html(setor.total);
      var ctx = document.getElementById('chamado_por_setor').getContext('2d');
      var meuGrafico = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            label: 'Total de chamados por setor',
            data: valores,
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 205, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(255, 205, 86)',
              'rgb(75, 192, 192)',
              'rgb(54, 162, 235)',
              'rgb(153, 102, 255)',
              'rgb(201, 203, 207)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          plugins: {
            legend: {
              display: false, // Exibir legenda
              position: 'bottom' // Posição da legenda (pode ser 'top', 'bottom', 'left' ou 'right')
            },
            datalabels: {
              formatter: function (value, context) {
                return value + " (" + context.chart.data.labels[context.dataIndex] + ")";
              },
              color: "#fff"
            }
          }
        }
      });
    }
  });
}




function BuscarChamadosPorStatus() {
  $.ajax({
    url: BASE_URL_AJAX("dashboard_dataview"),
    method: 'GET',
    dataType: 'json',
    data: { acao: 'chamado_status' },
    success: function (data) {
      var total_chamados = 0;
      var aguardando = 0;
      var em_atendimento = 0;
      var concluidos = 0;

      // Iterar sobre a matriz de objetos
      for (var i = 0; i < data.length; i++) {
        var chamado = data[i];

        total_chamados += parseInt(chamado.Total);
        aguardando += parseInt(chamado.Aguardando);
        em_atendimento += parseInt(chamado.Em_atendimento);
        concluidos += parseInt(chamado.Encerrando);
      }

      $("#total_chamados").html(total_chamados);
      $("#aguardando").html(aguardando);
      $("#em_atendimento").html(em_atendimento);
      $("#concluidos").html(concluidos);
      $("#qtd_chamado_por_status").html(total_chamados);

      var ctx = document.getElementById('chart_chamados_status').getContext('2d');
      var meuGrafico = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["O.S aguardando", "O.S em atendimento", "O.S finalizadas"],
          datasets: [{
            label: 'Total de O.S',
            data: [aguardando, em_atendimento, concluidos],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 205, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(255, 205, 86)',
              'rgb(75, 192, 192)',
              'rgb(54, 162, 235)',
              'rgb(153, 102, 255)',
              'rgb(201, 203, 207)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false, // Exibir legenda
              position: 'bottom' // Posição da legenda (pode ser 'top', 'bottom', 'left' ou 'right')
            },
            datalabels: {
              formatter: function (value, context) {
                return value + " (" + context.dataset.labels[context.dataIndex] + ")";
              },
              color: "#fff"
            }
          }
        }
      });
    }
  });
}

    </script>

</body>

</html>