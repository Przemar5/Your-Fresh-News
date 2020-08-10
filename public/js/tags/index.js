$(document).ready(function () {

    let createRow = (data) => {
        let url = 'https://yourfreshnews.herokuapp.com/tags/'
        let routeShow = url + data.tag.id
        let routeEdit = url + data.tag.id + '/edit'
        let routeUpdate = url + data.tag.id
        let routeDelete = url + data.tag.id
        let token = $('meta[name="csrf-token"]').attr('content')

        let row = '<tr>' +
            '<td>' + 
                '<a href="' + routeShow + '">' + 
                    data.tag.id + 
                '</a>' + 
            '</td>' +
            '<td>' + 
                data.tag.name + 
            '</td>' + 
            '<td class="d-flex justify-content-between d-block float-right" ' + 
            'style="max-width: 14rem;">' + 
                '<form action="' + routeEdit + '" method="PATCH" accept-charset="UTF-8" ' + 
                'class="form-edit" data-route="' + routeUpdate + '">' + 
                    '<input type="hidden" name="_method" value="PATCH">' +
                    '<input type="hidden" name="_token" value="' + token + '">' +
                    '<button type="submit" class="btn btn-sm btn-outline-primary">' + 
                        '<i class="fas fa-pencil-alt"></i>&nbsp;Edit' + 
                    '</button>' +
                '</form>' +
                '<form action="' + routeDelete + '" method="DELETE" accept-charset="UTF-8" ' + 
                'class="form-delete">' + 
                    '<input type="hidden" name="_method" value="DELETE">' +
                    '<input type="hidden" name="_token" value="' + token + '">' +
                    '<button type="submit" class="btn btn-sm btn-outline-danger">' + 
                        '<i class="fas fa-trash-alt"></i>&nbsp;Delete' + 
                    '</button>' + 
                '</form>' +
            '</td>' +
        '</tr>'

        $('tbody').prepend(row)
    }

    $('body').on('submit', '.form-create', function (e) {
        e.preventDefault()

        $(this).parsley()

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: e.target.dataset.route,
            method: 'POST',
            data: $(this).serialize(),
            success: (data) => {
                createRow(data)

                if ($('#table').hasClass('d-none')) {
                    $('#table').removeClass('d-none')
                    $('.no-results').addClass('d-none')
                }
            },
            fail: (data) => console.log('failure')
        })
    })

	$('body').on('submit', '.form-edit', function (e) {
		e.preventDefault()

		let row = $(this).closest('tr')
        let td = row.children('td').eq(1)

        if (! $(this).hasClass('edit-mode')) {
            let tagName = td.text().trim()
			td.text('')
			     .append('<input type="text" name="name" ' +
                'class="form-control input-sm" value="' + tagName + '" ' + 
                'maxlength="255" data-parsley-length="255" ' + 
                'data-parsley-pattern="[\\w\\-\\+_]+" ' + 
                'required style="padding: .1rem .5rem !important; height: 2rem;">')

        } else {
            let tagName = td.find('input').val()
            let updatedData = $(this).serialize() + '&name=' + tagName;
            let route = e.target.dataset.route

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            
            $.ajax({
                url: route,
                method: 'PATCH',
                data: updatedData,
                success: (data) => console.log('success'),
                fail: (data) => console.log('failure')
            })

            let text = td.find('input').val()
            td.html(text)
        }

        $(this).toggleClass('edit-mode')
	})

	$('body').on('submit', '.form-delete', function (e) {
		e.preventDefault()

		let row = $(e.target).closest('tr')
		let tagName = row.children('td').eq(1).text().trim()

		if (confirm('Are You sure you want remove tag: "' + tagName + '"?')) {
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			})

			$.ajax({
				url: $(this).attr('action'),
				method: 'DELETE',
         		contentType: 'application/json; charset=utf-8',
                success: (data) => {
                    if ($('#table tbody tr').length === 1) {
                        $('#table').addClass('d-none')
                        $('.no-results').removeClass('d-none')
                    }
                },
                fail: (data) => console.log('failure')
			})

			row.remove()
		}
	})
})