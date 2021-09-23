<!DOCTYPE html>
<html>
<head>
    <title>Lista de Postulantes</title>
    <style>



            /** Define the header rules **/
    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 3cm;
    }

    body {
                margin-top: 2cm;
                border-color: #0e3d80;
                border-width: 1em

            }

    #cabecera {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    font-size: x-small;
    }

    #customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    .center{
    text-align:center;
    }

    .right{
        text-align: right;
    }

    #customers td, #customers th {
    border: 1px solid #DDDDDD;
    font-size: x-small;
    padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #fff;}


    #customers tr:hover {
        background-color: #DDD;
        }

    #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    font-size: x-small;
    background-color: #0e3d80;
    color: white;
    }
    </style>
</head>
<body>
        <script type="text/php">
            if (isset($pdf)) {
              $font = $fontMetrics->getFont("Arial", "bold");
              $today = date("d/m/Y h:i:s");
              $pdf->page_text(535, 760, "Pagina {PAGE_NUM}", $font, 10, array(0, 0, 0));
              $pdf->page_text(40, 760, "Fecha de Impresión: {$today}", $font, 10, array(0, 0, 0));
            }
          </script>
    <header>
        <img src="{{public_path('img/logofull.png')}}" class="imagencentro" width="690" >
    </header>
    <br>
    <br>
    <h4 class="center">CONSTANCIA DE PRECALIFICACION AL SUBSIDIO</h4>
    <h4 class="center">En el marco de la Ley 5638/16</h4>
    <p>
        El Ministerio de Urbanismo, Vivienda y Habitat emite constancia de pre calificación al Aporte Estatal
        al Sr. {{ $task->name }} {{ $task->last_name }} con C.I. Nro {{ number_format((int)$task->government_id,0,".",".") }} en el marco de la Ley 5638/16 y su reglamentación
    </p>
    <p>
        El Aporte Estatal a ser otorgado corresponde a la categoria 4, el cual asciende a la suma de Gs {{ number_format((int)$task->amount,0,".",".")  }}, lo equivalentes
        al 20% de monto total de la carta oferta del inmueble individualizado como Finca Nro {{ $task->farm }} Cta. Cte. Ctral Nro {{ $task->account }}
        de la Ciudad de {{ $task->city->CiuNom }} del Departamento {{ ucwords(strtolower($task->state->DptoNom))  }}
    </p>
    <p>
        (La presente tendra una validez de 60 días. Queda prohibido su canjeada, adulterada o modificaciones. El desembolso está sujeto
        a la aprobación por parte de la IFI del crédito complementario)
    </p>
    <p class="right">
        Asuncion {{ $task->created_at }}
    </p>

    <img src="data:image/png;base64, {{ base64_encode($valor) }}" alt="">


</body>

</html>
