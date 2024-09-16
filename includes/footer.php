<footer class="main-footer">
    <strong>Copyright &copy;
        <script>
        document.write(new Date().getFullYear())
        </script>
        <a href="https://tenzsoft.com/#quote" style="color: #138771;">Tenz Soft</a>.
    </strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
</footer>
</div>
<!-- jQuery -->
<script src="<?php echo APP_URL; ?>/assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo APP_URL; ?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo APP_URL; ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!--Font Awesome-->
<script src="https://kit.fontawesome.com/0414d243a9.js" crossorigin="anonymous"></script>
<!-- ChartJS -->
<script src="<?php echo APP_URL; ?>/assets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo APP_URL; ?>/assets/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo APP_URL; ?>/assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo APP_URL; ?>/assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo APP_URL; ?>/assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo APP_URL; ?>/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js">
</script>
<!-- Summernote -->
<script src="<?php echo APP_URL; ?>/assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo APP_URL; ?>/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo APP_URL; ?>/assets/dist/js/adminlte.js"></script>
<!-- Include lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<!-- Fancybox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo APP_URL; ?>/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
    integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous">
</script>
<script>
$(document).ready(function() {
    function getFormattedFilename() {
        // Get the page title
        var pageTitle = document.title;

        // Get the current date and time in Karachi timezone
        var options = {
            timeZone: 'Asia/Karachi',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        var now = new Date().toLocaleString('en-GB', options);

        // Replace forward slashes with dashes (e.g., 31/07/2024 becomes 31-07-2024)
        now = now.replace(/[/]/g, '-').replace(/[,]/g, '');

        // Remove spaces between date and time (e.g., "31-07-2024 12-00-00" becomes "31-07-2024_12-00-00")
        now = now.replace(/ /g, '_').replace(/:/g, '-');

        // Construct the filename
        return pageTitle + "_" + now;
    }

    function customPrintAction(e, dt, button, config, selector) {
        var filename = getFormattedFilename();
        var table = dt;
        var colVis = table.columns().visible();
        var printContent =
            '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">';

        // Table Header
        printContent += '<thead>';
        $(selector).find('thead tr').each(function() {
            printContent += '<tr>';
            $(this).find('th').each(function() {
                var colspan = $(this).attr('colspan') || 1;
                var align = $(this).css('text-align') || 'center';
                printContent += '<th colspan="' + colspan +
                    '" style="border: 1px solid black; padding: 5px; font-family: Arial; font-size: 11px; background-color: #f2f2f2; text-align: ' +
                    align + '">' + $(this).text() + '</th>';
            });
            printContent += '</tr>';
        });
        printContent += '</thead><tbody>';

        // Table Body
        table.rows({
            search: 'applied'
        }).every(function() {
            var row = this.data();
            printContent += '<tr>';
            for (var i = 0; i < row.length; i++) {
                if (colVis[i]) {
                    var cellData = row[i];
                    var align = $(this.cell(i).node()).css('text-align') || 'center';
                    printContent +=
                        '<td style="border: 1px solid black; padding: 5px; font-family: Arial; font-size: 11px; text-align: ' +
                        align + '">' + cellData + '</td>';
                }
            }
            printContent += '</tr>';
        });
        printContent += '</tbody>';

        // Table Footer
        var $footer = $(selector).find('tfoot').clone();
        if ($footer.length > 0) {
            var footerContent = '<tfoot>';
            $footer.find('tr').each(function() {
                footerContent += '<tr>';
                $(this).find('th, td').each(function() {
                    var colspan = $(this).attr('colspan') || 1;
                    var align = $(this).css('text-align') || 'center';
                    footerContent += '<td colspan="' + colspan +
                        '" style="border: 1px solid black; padding: 5px; font-family: Arial; font-size: 11px; background-color: #f2f2f2; text-align: ' +
                        align + '">' + $(this).text() + '</td>';
                });
                footerContent += '</tr>';
            });
            footerContent += '</tfoot>';
            printContent += footerContent;
        }

        printContent += '</table>';

        var pageTitle = document.title;
        var imageSrc =
            '<?php echo APP_URL; ?>/assets/img/uploads/client-logo.png'; // Update with correct path if needed

        // Get the Filter values
        var fromDate = $('#from').val() || '';
        var toDate = $('#to').val() || '';
        var filterType = $('#filterType').val() || '';
        var filterName = $('#filterName').val() || '';
        var filterStage = $('#filterStage').val() || '';

        var dateHtml = '';
        if (fromDate && toDate) {
            dateHtml =
                '<div style="text-align: right;"><strong>From Date:</strong> ' +
                fromDate + '<br><strong>To Date:</strong> ' + toDate + '</div>';
        }

        var filterHtml = '';
        if (filterType || filterName || filterStage) {
            filterHtml = '<div style="text-align: left;">';
            if (filterType) {
                filterHtml += '<strong>Type:</strong> ' + filterType +
                    '&nbsp;&nbsp;';
            }
            if (filterName) {
                filterHtml += '<strong>Name:</strong> ' + filterName +
                    '&nbsp;&nbsp;';
            }
            if (filterStage) {
                filterHtml += '<strong>Stage:</strong> ' + filterStage +
                    '&nbsp;&nbsp;';
            }
            filterHtml += '</div>';
        }

        var win = window.open('', '_blank');
        win.document.write(
            '<html><head><title>&nbsp;</title>' +
            '<style>' +
            'body { font-family: Arial; font-size: 11px; } ' +
            '.text-center { text-align: center; } ' +
            '.text-right { text-align: right; } ' +
            '.text-bold { font-weight: bold; } ' +
            '.text-black-20 { color: #333; } ' +
            '.bg-gray-light { background-color: #f2f2f2; }' +
            'table { width: 100%; border-collapse: collapse; } ' +
            'th, td { border: 1px solid black; padding: 5px; font-family: Arial; font-size: 11px; }' +
            // Margin-bottom to the Logo
            'img#companyLogo { margin-bottom: 10px; }' +
            // Styles for title
            '#pageTitle { font-size: 14px; font-weight: bold; text-align: center; margin-bottom: 10px; }' +
            '</style>' +
            '</head><body>' +
            // Displaying Logo, title, and dates
            '<div style="display: flex; justify-content: space-between; align-items: center;">' +
            '<div style="text-align: left;"><img id="companyLogo" src="' +
            imageSrc + '" width="50"></div>' +
            dateHtml +
            '</div>' +
            filterHtml +
            '<div class="page-title" id="pageTitle">' + pageTitle + '</div>' +
            printContent +
            '<script>' +
            'document.getElementById("companyLogo").onload = function() { window.print(); window.onafterprint = function() { window.close(); }; };' +
            '<\/script>' +
            '</body></html>'
        );
        win.document.title = filename;
        win.document.close();
    }

    function initDataTable(selector, additionalOptions = {}) {
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        }

        var defaultOptions = {
            "pageLength": preferences.length,
            "paging": true,
            "page": preferences.page,
            "lengthChange": true,
            "autoWidth": false,
            "responsive": true,
            "buttons": [
                "copy",
                {
                    extend: 'csv',
                    title: getFormattedFilename
                },
                {
                    extend: 'excel',
                    title: getFormattedFilename
                },
                {
                    extend: 'print',
                    customize: function(win) {
                        $(win.document.body).css('font-family', 'Arial');
                        $(win.document.body).find('table').addClass('compact').css('font-size',
                            '11px');
                    },
                    action: function(e, dt, button, config) {
                        customPrintAction(e, dt, button, config, selector);
                    }
                },
                "colvis"
            ],
            // B for buttons
            // l for length change input
            // f for the filtering (search) input
            // r for the processing display element
            // t for the table
            // i for the table information summary
            // p for the pagination control
            "dom": 'l<"d-flex justify-content-between"Bf>rt<"d-flex justify-content-between"ip>',
            ...additionalOptions
        };

        var options = $.extend(true, {}, defaultOptions, additionalOptions);
        var table = $(selector).DataTable(options);
        table.buttons().container().appendTo($(selector + '_wrapper .col-md-6:eq(0)'));
        return table;
    }

    var tableConfigs = {
        "#CashBookTable": {
            order: [
                [1, 'asc']
            ]
        },
        "#ReportTable": {
            order: []
        }
    };

    for (var selector in tableConfigs) {
        if (tableConfigs.hasOwnProperty(selector)) {
            initDataTable(selector, tableConfigs[selector]);
        }
    }
});



$(document).ready(function() {
    // Date Picker
    var dateFormat = "mm/dd/yy",
        from = $("#from").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1
        }).on("change", function() {
            to.datepicker("option", "minDate", getDate(this));
        }),
        to = $("#to").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1
        }).on("change", function() {
            from.datepicker("option", "maxDate", getDate(this));
        });

    function getDate(element) {
        try {
            return $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            return null;
        }
    }

    // Initialize Fancybox for images
    $('[data-fancybox="images"]').fancybox({
        buttons: ['zoom', 'slideShow', 'fullScreen', 'download', 'thumbs', 'close'],
        loop: true
    });

    // Function to handle printing
    function printPage(date, title, tableId) {
        setLandscapeOrientation();
        var header = `<div style="text-align: center;"><h3>${title}</h3><h4>Date: ${date}</h4></div>`;
        var footer = '<div style="text-align: center;">Footer content</div>';
        var visibleColumns = [];

        // Get the indexes of visible columns
        $(`${tableId} thead th`).each(function(index) {
            if ($(this).css('display') !== 'none') {
                visibleColumns.push(index);
            }
        });

        // Construct HTML for visible columns only
        var bodyHtml = '';
        $(`${tableId} tbody tr`).each(function() {
            var rowHtml = '<tr>';
            $(this).find('td').each(function(index) {
                if (visibleColumns.includes(index)) {
                    rowHtml += `<td>${$(this).html()}</td>`;
                }
            });
            rowHtml += '</tr>';
            bodyHtml += rowHtml;
        });

        // Combine header, body, and footer HTML
        var html = header + '<table>' + $(`${tableId} thead`).html() + bodyHtml + '</table>' + footer;

        // Open a new window and print
        var printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write(
            `<html><head><title>${title}</title><style>@media print { @page { size: landscape; }} body { font-family: Arial, sans-serif; }</style></head><body>${html}</body></html>`
        );
        printWindow.document.close();
        printWindow.print();
        printWindow.onafterprint = function() {
            resetOrientation();
        };
    }

    // Function to set page orientation to landscape
    function setLandscapeOrientation() {
        if (window.matchMedia) {
            var mediaQueryList = window.matchMedia('print');
            if (mediaQueryList.matches) {
                var style = document.createElement('style');
                style.appendChild(document.createTextNode('@page { size: landscape; }'));
                document.head.appendChild(style);
            }
        }
    }

    // Function to reset page orientation to portrait after printing
    function resetOrientation() {
        var style = document.querySelector('style');
        if (style) {
            document.head.removeChild(style);
        }
    }

});


// Function to format CNIC
function formatCnic(cnic) {
    // Ensure the CNIC is 13 digits long before formatting
    if (cnic.length == 13) {
        return cnic.substring(0, 5) + '-' + cnic.substring(5, 12) + '-' + cnic.substring(12);
    }
    return cnic; // Return the original CNIC if it's not 13 digits
}

// Function to format phone numbers
function formatPhoneNo(phoneNo) {
    // Ensure the phone number is at least 10 digits long before formatting
    if (phoneNo.length >= 10) {
        let len = phoneNo.length;
        let prefix = phoneNo.substring(0, len - 7);
        let mainPart = phoneNo.substring(len - 7);
        return prefix + '-' + mainPart;
    }
    return phoneNo; // Return the original phone number if it's not at least 10 digits
}

// Function to format Date into desired format
function formatDate(dateString) {
    const date = new Date(dateString);
    const month = String(date.getMonth() + 1).padStart(2, '0'); // getMonth() returns 0-11
    const day = String(date.getDate()).padStart(2, '0');
    const year = date.getFullYear();
    return `${month}/${day}/${year}`;
}


// Function to add separators for the Asian number system
function thSeparator(number) {
    let isNegative = false;
    if (number < 0) {
        isNegative = true;
        number = Math.abs(number);
    }

    number = number.toString();
    const len = number.length;
    let formattedNumber = '';

    if (len > 3) {
        const lastThree = number.slice(-3);
        let remaining = number.slice(0, len - 3);
        formattedNumber = lastThree;

        while (remaining.length > 2) {
            const lastTwo = remaining.slice(-2);
            remaining = remaining.slice(0, -2);
            formattedNumber = lastTwo + ',' + formattedNumber;
        }

        if (remaining.length > 0) {
            formattedNumber = remaining + ',' + formattedNumber;
        }
    } else {
        formattedNumber = number;
    }

    return isNegative ? '-' + formattedNumber : formattedNumber;
}

// Function to add thousand separators
function thSeparatorGlobal(number, decimals) {
    // Check if the number is not null or undefined
    if (number === null || number === undefined) {
        return '';
    }

    number = parseFloat(number);
    // Convert the number to a string and split it into integer and decimal parts
    var parts = number.toFixed(decimals).toString().split('.');
    var integerPart = parts[0];
    var decimalPart = parts.length > 1 ? '.' + parts[1] : '';

    // Add thousand separators to the integer part
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // Concatenate the integer and decimal parts
    return integerPart + decimalPart;
}

function makeDropdownReadonly(elementId) {
    document.addEventListener('DOMContentLoaded', function() {
        // Make the dropdown readonly
        document.getElementById(elementId)
            .addEventListener(
                'mousedown',
                function(e) {
                    e.preventDefault();
                    this.blur();
                    return false;
                });
    });
}

// LocalStorage listener to potentially reload a page based on URL
window.addEventListener('storage', function(event) {
    if (event.key === 'reload' && event.newValue) {
        var data = JSON.parse(event.newValue);
        if (data.urls && Array.isArray(data.urls)) {
            var currentUrl = window.location.href;

            // Check if the current URL matches any in the list
            for (var i = 0; i < data.urls.length; i++) {
                if (currentUrl.includes(data.urls[i])) {
                    location.reload();
                    break;
                }
            }
        }
    }
});
</script>