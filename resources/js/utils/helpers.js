export function uniqId(prefix = 'id') {
    return prefix + Math.random().toString(36).substring(2, 11);
}

export function calculateCharge(value, total, type) {
    if (value && type == 'percent') {
        return (value / 100) * total;
    }

    if (value && type == 'fixed') {
        return value;
    }

    return 0;
}

export function printByDomElId(id, head_children = '', callback = null) {
    const win = window.open("", "PRINT");
    const html = document.getElementById(id).innerHTML;

    win.document.write(`
        <!DOCTYPE html>
        <html>
            <head>
                <link href="/css/bootstrap.min.css" rel="stylesheet">
                ${head_children}
            </head>
            <body>
                ${html}
            </body>
        </html>
    `);

    setTimeout(() => {
        win.document.close();
        win.focus();
        win.print();
        win.close();

        if (typeof callback == "function") {
            callback();
        }
    }, 500);
}

export function numberToWords(num) {
    const ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    const tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    const teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];

    if (num === 0) return 'zero';

    if (num < 0) return 'minus ' + numberToWords(Math.abs(num));

    let words = '';

    if (Math.floor(num / 1000) > 0) {
        words += numberToWords(Math.floor(num / 1000)) + ' thousand ';
        num %= 1000;
    }

    if (Math.floor(num / 100) > 0) {
        words += ones[Math.floor(num / 100)] + ' hundred ';
        num %= 100;
    }

    if (num >= 10 && num < 20) {
        words += teens[num - 10] + ' ';
        num = 0;
    }

    else if (Math.floor(num / 10) > 0) {
        words += tens[Math.floor(num / 10)] + ' ';
        num %= 10;
    }

    if (num > 0) {
        words += ones[num] + ' ';
    }

    return words.trim();
}

export function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
