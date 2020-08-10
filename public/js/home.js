$(document).ready(function () {

	let changeSlides
	let interval = 7000

	
	$('body').on('click', '.carousel-custom-caption', (e) => {
		let next = e.target.dataset.slideTo

		activateSlide(next)
		hideOtherSlides(next)
		clearInterval(changeSlides)
		changeSlides = setInterval(() => nextSlide(), interval)
	})


	$('body').on('click', '.carousel-custom-control-prev', () => {
		previousSlide()
		clearInterval(changeSlides)
		changeSlides = setInterval(() => nextSlide(), interval)
	})


	$('body').on('click', '.carousel-custom-control-next', () => {
		nextSlide()
		clearInterval(changeSlides)
		changeSlides = setInterval(() => nextSlide(), interval)
	})


	const previousSlide = () => changeSlideByOne(-1)


	const nextSlide = () => changeSlideByOne(1)


	const changeSlideByOne = (val = 1) => {
		let captions = $('#carousel').find('.carousel-custom-caption')

		for (i = 0; i < captions.length; i++) {
			if ($(captions[i]).hasClass('active')) {
				break
			}
		}

		let next = (i + val) % captions.length

		activateSlide(next)
		hideOtherSlides(next)
	}


	const activateSlide = (index) => {
		$($('.carousel-custom-slide')[index]).animate({
			opacity: 1
		}, 300, () => true)

		setTimeout(() => {
			$($('.carousel-custom-slide')[index]).css('z-index', '10')
		}, 1)
		
		let caption = $('.carousel-custom-caption')[index]

		$(caption).animate({
			opacity: 1
		}, 300, () => true)
		$(caption).addClass('active')
	}


	const hideSlide = (index) => {
		$($('.carousel-custom-slide')[index]).animate({
			opacity: 0
		}, 300, () => true)

		setTimeout(() => {
			$($('.carousel-custom-slide')[index]).css('z-index', '-1')
		}, 300)
		
		let caption = $('.carousel-custom-caption')[index]

		$(caption).animate({
			opacity: .4
		}, 300, () => true)
		$(caption).removeClass('active')
	}


	const hideOtherSlides = (index) => {
		$('.carousel-custom-slide').each((e) => {
			if (e != index) {
				hideSlide(e)
			}
		})
	}


	const hideSlides = () => {
		$('.carousel-custom-slide').each((e) => hideSlide(e))
	}


	const initSlider = () => {
		activateSlide(0)
		hideOtherSlides(0)
		clearInterval(changeSlides)
		changeSlides = setInterval(() => nextSlide(), interval)
	}

	initSlider()

})