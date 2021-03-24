<table class="table table-bordered" id="data-table">
	<thead>
		<th>S.No</th>
		<th>Attachment</th>
	</thead>
	<tbody>
		<?php 
		$imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
					?>
		@foreach ($attachments as $key=>$attachment)
			<tr>
				<td>{{ $loop->index+1 }}</td>
				<td class="text-center">
					@if (in_array(File::extension($attachment), $imageExtensions))
					<a href="{{ asset('theme/images/rfq_comment_attachment/'.$attachment) }}?text={{ $key }}" data-toggle="lightbox" data-title="Attachments" data-gallery="gallery">
                      <img src="{{ asset('theme/images/rfq_comment_attachment/'.$attachment) }}?text={{ $key }}" class="img-fluid mb-2" alt="Attachment" width="20%" height="30%" />
                    </a>
                    @else
						<a href="{{ url('admin/download-rfq-comments-attachment/'.$key) }}">
							<i class="fa fa-download"></i>&nbsp;Download 
							<span style="text-transform: capitalize;">
								{{ File::extension($attachment) }}
							</span>
						</a>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>