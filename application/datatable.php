<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
</head>
<style>
    body {
        margin: 2em;
    }
    ul.dt-button-collection {
        background-color: #e5e5e5;
        border: 1px solid #c0c0c0;
    }
    li.dt-button a:hover {
        background-color: transparent;
        color: #115094;
        font-weight: bold;
    }
    li.dt-button.active a,
    li.dt-button.active a:hover,
    li.dt-button.active a:focus {
        color: #337ab6;
        background-color: transparent;
        font-weight: bold;
    }
    li.dt-button.active a::before {
        content: "✔ ";
    }
    .dataTables_info {
        font-size: 0.8em;
        margin-top: -12px;
        text-align: right;
    }
    .previous a,
    .next a {
        font-weight: bold;
    }

</style>
<a class="btn btn-success" style="margin-bottom:16px;" href="https://codepen.io/collection/XKgNLN/" target="_blank">Other examples on Codepen</a>
<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Order</th>
        <th>Description</th>
        <th>Deadline</th>
        <th>Status</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>Alphabet puzzle</td>
        <td>2016/01/15</td>
        <td>Done</td>
        <td data-order="1000">€1.000,00</td>
    </tr>
    <tr>
        <td>2</td>
        <td>Layout for poster</td>
        <td>2016/01/31</td>
        <td>Planned</td>
        <td data-order="1834">€1.834,00</td>
    </tr>
    <tr>
        <td>3</td>
        <td>Image creation</td>
        <td>2016/01/23</td>
        <td>To Do</td>
        <td data-order="1500">€1.500,00</td>
    </tr>
    <tr>
        <td>4</td>
        <td>Create font</td>
        <td>2016/02/26</td>
        <td>Done</td>
        <td data-order="1200">€1.200,00</td>
    </tr>
    <tr>
        <td>5</td>
        <td>Sticker production</td>
        <td>2016/02/18</td>
        <td>Planned</td>
        <td data-order="2100">€2.100,00</td>
    </tr>
    <tr>
        <td>6</td>
        <td>Glossy poster</td>
        <td>2016/03/17</td>
        <td>To Do</td>
        <td data-order="899">€899,00</td>
    </tr>
    <tr>
        <td>7</td>
        <td>Beer label</td>
        <td>2016/05/28</td>
        <td>Confirmed</td>
        <td data-order="2499">€2.499,00</td>
    </tr>
    <tr>
        <td>8</td>
        <td>Shop sign</td>
        <td>2016/04/19</td>
        <td>Offer</td>
        <td data-order="1099">€1.099,00</td>
    </tr>
    <tr>
        <td>9</td>
        <td>X-Mas decoration</td>
        <td>2016/10/31</td>
        <td>Confirmed</td>
        <td data-order="1750">€1.750,00</td>
    </tr>
    <tr>
        <td>10</td>
        <td>Halloween invite</td>
        <td>2016/09/12</td>
        <td>Planned</td>
        <td data-order="400">€400,00</td>
    </tr>
    <tr>
        <td>11</td>
        <td>Wedding announcement</td>
        <td>2016/07/09</td>
        <td>To Do</td>
        <td data-order="299">€299,00</td>
    </tr>
    <tr>
        <td>12</td>
        <td>Member pasport</td>
        <td>2016/06/22</td>
        <td>Offer</td>
        <td data-order="149">€149,00</td>
    </tr>
    <tr>
        <td>13</td>
        <td>Drink tickets</td>
        <td>2016/11/01</td>
        <td>Confirmed</td>
        <td data-order="199">€199,00</td>
    </tr>
    <tr>
        <td>14</td>
        <td>Album cover</td>
        <td>2017/03/15</td>
        <td>To Do</td>
        <td data-order="4999">€4.999,00</td>
    </tr>
    <tr>
        <td>15</td>
        <td>Shipment box</td>
        <td>2017/02/08</td>
        <td>Offer</td>
        <td data-order="1399">€1.399,00</td>
    </tr>

    <tr>
        <td>16</td>
        <td>Wooden puzzle</td>
        <td>2017/01/11</td>
        <td>Done</td>
        <td data-order="1000">€1.000,00</td>
    </tr>
    <tr>
        <td>17</td>
        <td>Fashion Layout</td>
        <td>2016/01/30</td>
        <td>Planned</td>
        <td data-order="1834">€1.834,00</td>
    </tr>
    <tr>
        <td>18</td>
        <td>Toy creation</td>
        <td>2016/01/10</td>
        <td>To Do</td>
        <td data-order="1550">€1.550,00</td>
    </tr>
    <tr>
        <td>19</td>
        <td>Create stamps</td>
        <td>2016/02/26</td>
        <td>Done</td>
        <td data-order="1220">€1.220,00</td>
    </tr>
    <tr>
        <td>20</td>
        <td>Sticker design</td>
        <td>2017/02/18</td>
        <td>Planned</td>
        <td data-order="2100">€2.100,00</td>
    </tr>
    <tr>
        <td>21</td>
        <td>Poster rock concert</td>
        <td>2017/04/17</td>
        <td>To Do</td>
        <td data-order="899">€899,00</td>
    </tr>
    <tr>
        <td>22</td>
        <td>Wine label</td>
        <td>2017/05/28</td>
        <td>Confirmed</td>
        <td data-order="2799">€2.799,00</td>
    </tr>
    <tr>
        <td>23</td>
        <td>Shopping bag</td>
        <td>2017/04/19</td>
        <td>Offer</td>
        <td data-order="1299">€1.299,00</td>
    </tr>
    <tr>
        <td>24</td>
        <td>Decoration for Easter</td>
        <td>2017/10/31</td>
        <td>Confirmed</td>
        <td data-order="1650">€1.650,00</td>
    </tr>
    <tr>
        <td>25</td>
        <td>Saint Nicolas colorbook</td>
        <td>2017/09/12</td>
        <td>Planned</td>
        <td data-order="510">€510,00</td>
    </tr>
    <tr>
        <td>26</td>
        <td>Wedding invites</td>
        <td>2017/07/09</td>
        <td>To Do</td>
        <td data-order="399">€399,00</td>
    </tr>
    <tr>
        <td>27</td>
        <td>Member pasport</td>
        <td>2017/06/22</td>
        <td>Offer</td>
        <td data-order="249">€249,00</td>
    </tr>
    <tr>
        <td>28</td>
        <td>Drink tickets</td>
        <td>2017/11/01</td>
        <td>Confirmed</td>
        <td data-order="199">€199,00</td>
    </tr>
    <tr>
        <td>29</td>
        <td>Blue-Ray cover</td>
        <td>2018/03/15</td>
        <td>To Do</td>
        <td data-order="1999">€1.999,00</td>
    </tr>
    <tr>
        <td>30</td>
        <td>TV carton</td>
        <td>2019/02/08</td>
        <td>Offer</td>
        <td data-order="1369">€1.369,00</td>
    </tr>
    </tbody>
</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script>
    $(document).ready(function () {
        //Only needed for the filename of export files.
        //Normally set in the title tag of your page.document.title = 'Simple DataTable';
        //Define hidden columns
        var hCols = [3, 4];
        // DataTable initialisation
        $("#example").DataTable({
            dom:
                "<'row'<'col-sm-4'B><'col-sm-2'l><'col-sm-6'p<br/>i>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p<br/>i>>",
            paging: true,
            autoWidth: true,
            columnDefs: [
                {
                    visible: false,
                    targets: hCols
                }
            ],
            buttons: [
                {
                    extend: "colvis",
                    collectionLayout: "three-column",
                    text: function () {
                        var totCols = $("#example thead th").length;
                        var hiddenCols = hCols.length;
                        var shownCols = totCols - hiddenCols;
                        return "Columns (" + shownCols + " of " + totCols + ")";
                    },
                    prefixButtons: [
                        {
                            extend: "colvisGroup",
                            text: "Show all",
                            show: ":hidden"
                        },
                        {
                            extend: "colvisRestore",
                            text: "Restore"
                        }
                    ]
                },
                {
                    extend: "collection",
                    text: "Export",
                    buttons: [
                        {
                            text: "Excel",
                            extend: "excelHtml5",
                            footer: false,
                            exportOptions: {
                                columns: ":visible"
                            }
                        },
                        {
                            text: "CSV",
                            extend: "csvHtml5",
                            fieldSeparator: ";",
                            exportOptions: {
                                columns: ":visible"
                            }
                        },
                        {
                            text: "PDF Portrait",
                            extend: "pdfHtml5",
                            message: "",
                            exportOptions: {
                                columns: ":visible"
                            }
                        },
                        {
                            text: "PDF Landscape",
                            extend: "pdfHtml5",
                            message: "",
                            orientation: "landscape",
                            exportOptions: {
                                columns: ":visible"
                            }
                        }
                    ]
                }
            ],
            oLanguage: {
                oPaginate: {
                    sNext: '<span class="pagination-default">&#x276f;</span>',
                    sPrevious: '<span class="pagination-default">&#x276e;</span>'
                }
            },
            initComplete: function (settings, json) {
                // Adjust hidden columns counter text in button -->
                $("#example").on("column-visibility.dt", function (
                    e,
                    settings,
                    column,
                    state
                ) {
                    var visCols = $("#example thead tr:first th").length;
                    //Below: The minus 2 because of the 2 extra buttons Show all and Restore
                    var tblCols =
                        $(".dt-button-collection li[aria-controls=example] a").length - 2;
                    $(".buttons-colvis[aria-controls=example] span").html(
                        "Columns (" + visCols + " of " + tblCols + ")"
                    );
                    e.stopPropagation();
                });
            }
        });
    });

</script>