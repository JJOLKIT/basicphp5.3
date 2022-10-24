var myeditor;
function setCkEditor(id){
	
	ClassicEditor
			.create( document.querySelector( '#'+id ), {
				extraPlugins : [MyCustomUploadAdapterPlugin],
				fontFamily : {
					options : [
						'default',
						'노토산스KR, Noto Sans KR, sans-serif',
						'노토SerifKR, Noto Serif KR, serif',
						'나눔고딕, Nanum Gothic, sans-serif',
						'굴림, gulim, Gulim, sans-serif',
						'돋움, dotum, Dotum, sans-serif',
						'궁서, 궁서체, sans-serif',
						'명조, 명조체, sans-serif',
						'Arial, Arial, sans-serif',
					]
				},
				/*
				heading: {
					options: [
						{ model: 'heading1', view: 'span', title: '대제목', class: 'ck-heading_heading1' },
						{ model: 'heading2', view: 'h4', title: 'Heading 2', class: 'ck-heading_heading2' }
					]
				},
				*/
				fontSize : {
						options : [
							'default',
							'10px',
							'11px',
							'12px',
							'14px',
							'16px',
							'18px',
							'20px',
							'24px',
							'28px',
							'32px',
							'40px',
						]
					},
				toolbar: {
					items: [
						'heading',
						'|',
						'fontFamily',
						'fontSize',
						'fontColor',
						'fontBackgroundColor',
						'highlight',
						'alignment',
						'|',
						'bold',
						'italic',
						'strikethrough',
						'underline',
						'specialCharacters',
						'link',
						
						'|',
						'bulletedList',
						'numberedList',
						'indent',
						'outdent',
						
						'horizontalLine',
						'|',
						'imageUpload',
						'blockQuote',
						'insertTable',
						'mediaEmbed',
						'undo',
						'redo'
					]
				},
				language: 'ko',
				image: {
					styles : [
						'alignLeft', 'alignCenter', 'alignRight', 'full', 'side'
					],
					resizeOptions : [
						{
							name: 'imageResize:original',
							label : 'Original',
							value : null
						},
						{
							name : 'imageResize:50',
							label : '50%',
							value : '50'
						},
						{
							name : 'imageResize:75',
							label : '75%',
							value : '75'
						}
					],
					toolbar: [
						'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
						'|',
						'imageResize',
						'|',
						'imageTextAlternative',
						/*
						'imageStyle:full',
						'imageStyle:side'
						*/
					]
				},
				table: {
					contentToolbar: [
						'tableColumn',
						'tableRow',
						'mergeTableCells',
						'tableProperties',
						'TableCellProperties'
					]
				},
				licenseKey: '', 
				
				
			} )
			.then( editor => {
				myeditor = editor;
				
				//return myeditor;
			
				
			} )
			.catch( error => {
				console.error( 'Oops, something went wrong!' );
				console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
				console.warn( 'Build id: kot1h0uykkp0-7hpzopwxhnbt' );
				console.error( error );
			} );


			
}
