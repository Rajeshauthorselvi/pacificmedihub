<table class="table table-bordered" id="data-table">
	<thead>
		<th>S.No</th>
		<th>Image</th>
		<th>Message</th>
		<th>Created Date</th>
	</thead>
	<tbody>
		<?php 
		$imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
					?>
		@foreach ($purchase_attachments as $key=>$attachment)
			<tr>
				<td>{{ $loop->index+1 }}</td>
				<td class="text-center">
					@if(File::extension($attachment->attachment)!="pdf")
					<a href="{{ asset('theme/images/purchase_attachment/'.$attachment->attachment) }}?text={{ $key }}" data-toggle="lightbox" data-title="Attachments" data-gallery="gallery">
                      <img src="{{ asset('theme/images/purchase_attachment/'.$attachment->attachment) }}?text={{ $key }}" class="img-fluid mb-2" alt="Attachment" width="50%" />
                    </a>
					@else
						<a href="{{ url('admin/downlad-purchase-attachments/'.$attachment->id) }}">
							<i class="fa fa-download"></i>&nbsp;Download 
							<span style="text-transform: capitalize;">
								{{ File::extension($attachment->attachment) }}
							</span>
						</a>
					@endif
				</td>
				<td>{!! $attachment->comments !!}</td>
				<td>{{ date('d-m-Y',strtotime($attachment->created_at)) }}</td>
			</tr>
		@endforeach
	</tbody>
</table>