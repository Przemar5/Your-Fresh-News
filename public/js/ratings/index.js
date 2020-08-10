$(document).ready(function () {

    let createRow = (data) => {
        let url = 'https://yourfreshnews.herokuapp.com/ratings/''
        let routeShow = url + data.rating.slug
        let routeEdit = url + data.rating.slug + '/edit'
        let routeUpdate = url + data.rating.id
        let routeDelete = url + data.rating.id
        let token = $('meta[name="csrf-token"]').attr('content')

        let row = '<tr>' +
            '<td>' + 
                '<a href="' + routeShow + '">' + 
                    data.rating.id + 
                '</a>' + 
            '</td>' +
            '<td>' + 
                data.rating.type + 
            '</td>' + 
            '<td>' + 
                data.rating.title + 
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

        $('tbody').append(row)
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

        let requiredColumns = [
            {
                name: 'name', 
                index: 1
            }, 
            {
                name: 'slug', 
                index: 2
            }
        ]

		// let editButton = $(e.target).find('button')
		// let editButtonContent = editButton.html()
		// let editButtonTextLength = editButton.text().trim().length
		let row = $(this).closest('tr')
        let tds = []
        let rating = []

        for (i in requiredColumns) {
            tds[i] = row.children('td').eq(requiredColumns[i].index)
            rating[requiredColumns[i].name] = tds[i].text().trim()
        }

        if (! $(this).hasClass('edit-mode')) {
			tds[0].text('')
			tds[0].append('<input type="text" name="name" ' +
                'class="form-control input-sm" value="' + rating.name + '" ' + 
                'maxlength="255" data-parsley-length="255" ' + 
                'data-parsley-pattern="[\\w\\-\\+\\#\\$\\%\\^\\&\\@\\!\\(\\)\\|\\=\\,\\.\\<\\>\\/\\?\\" ]+" required ' +
                'style="padding: .1rem .5rem !important; height: 2rem;">')
			tds[1].text('')
			tds[1].append('<input type="text" name="slug" ' + 
                'class="form-control input-sm" value="' + rating.slug + '" ' + 
                'maxlength="255" data-parsley-length="255" ' + 
                'data-parsley-pattern="[\\w\\-]+" required ' + 
                'style="padding: .25rem .5rem !important; height: 2rem;">')

        } else {
            let name = $(e.target).closest('tr').children('td').eq(1).find('input').val()
            let slug = $(e.target).closest('tr').children('td').eq(2).find('input').val()
            let updatedData = $(e.target).serialize() + '&name=' + name + '&slug=' + slug;
            let route = e.target.dataset.route

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    // 'Content-Type': 'application/json; charset=UTF-8'
                }
            })
            
            $.ajax({
                url: route,
                method: 'PATCH',
                // contentType: 'application/json; charset=utf-8',
                // withCredentials: true,
                data: updatedData,
                success: (data) => console.log('success'),
                fail: (data) => console.log('failure')
            })

            for (i in tds) {
                let text = tds[i].find('input').val()
                tds[i].html(text)
            }
        }

        $(this).toggleClass('edit-mode')
	})

	$('body').on('submit', '.form-delete', function (e) {
		e.preventDefault()

		let row = $(e.target).closest('tr')
		let ratingName = row.children('td').eq(1).text().trim()

		if (confirm('Are You sure you want remove rating: "' + ratingName + '"?')) {
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