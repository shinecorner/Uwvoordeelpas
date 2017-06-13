$('#usersSearch').search({
    apiSettings: {
        url: baseUrl + 'ajax/users?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        description: 'email',
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    },
    onSelect: function(response) {
        $('input[name="user_id"]').val(response.id);
    }
});

$('#guestsSearch').search({
    apiSettings: {
        url: baseUrl + 'ajax/guests/' + $('input[name="company_id"]').val() + '?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        description: 'email',
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    },
    onSelect: function(response) {
        $('input[name="name"]').val(response.name);
        $('input[name="email"]').val(response.email);
        $('input[name="phone"]').val(response.phone);
    }
});

$('#usersCompaniesSearch1').search({
    apiSettings: {
        url: baseUrl + 'ajax/companies/users?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        url: 'link'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    }
});

$('#usersCompaniesSearch2, #usersCompaniesSearch2-2, #usersCompaniesSearch2-3').search({
    apiSettings: {
        url: baseUrl + 'ajax/companies/users?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        image: 'image',
        url: 'link'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    }
});

$('#companiesSearch').search({
    apiSettings: {
        url: baseUrl + 'ajax/companies?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        url: 'link'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    }
});

$('#invoicesSearch').search({
    apiSettings: {
        url: baseUrl + 'ajax/companies/invoices?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        url: 'link'
    },
    minCharacters: 3,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    }
});

$('#companiesOwnersSearch').search({
    apiSettings: {
        url: baseUrl + 'ajax/companies/owners?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        description: 'email'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    },
    onSelect: function(response) {
        $('input[name="owner"]').val(response.id);
    }
});

$('#companiesCallerSearch').search({
    apiSettings: {
        url: baseUrl + 'ajax/companies/callers?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        description: 'email'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    },
    onSelect: function(response) {
        $('input[name="caller"]').val(response.id);
    }
});

$('#companiesWaitersSearch').search({
    apiSettings: {
        url: baseUrl + 'ajax/companies/waiters?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        description: 'email'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    },
    onSelect: function(response) {
        $('input[name="waiter"]').val(response.id);
    }
});

$('#barcodesCompaniesSearch').search({
    apiSettings: {
        url: baseUrl + 'ajax/companies/barcodes?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        url: 'link'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    }
});

$('#affiliateSearch-1').search({
    apiSettings: {
        url: baseUrl + 'ajax/affiliates?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        image: 'image',
        url: 'link',
        description: 'commission'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    }
});

$('#affiliateSearch-2').search({
    apiSettings: {
        url: baseUrl + 'ajax/affiliates?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        image: 'image',
        url: 'link',
        description: 'commission'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    }
});

$('#affiliateSearch-3').search({
    apiSettings: {
        url: baseUrl + 'ajax/affiliates?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        image: 'image',
        description: 'commission'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    },
    onSelect: function(response) {
        $('input[name="program_id"]').val(response.program_id);
        $('.ui.normal.dropdown.network').dropdown('set selected', response.affiliate_network);
    }
});

$('#affiliateSearch-4').search({
    apiSettings: {
        url: baseUrl + 'ajax/affiliates?q={query}'
    },
    fields: {
        results: 'items',
        title: 'name',
        image: 'image',
        description: 'commission'
    },
    minCharacters: 2,
    maxResults: 15,
    error: {
        noResults: 'Er zijn geen zoekresultaten gevonden.',
        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
    },
    onSelect: function(response) {
        $('input[name="program_id"]').val(response.program_id);
        $('.ui.normal.dropdown.network').dropdown('set selected', response.affiliate_network);
    }
});

$('#affiliateSearchForm input').on('keypress', function(event) {
    if (event.which === 13) {
        $(this).submit();
    }
});