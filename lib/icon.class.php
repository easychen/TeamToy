<?php 

class Icon 
{
	function createIcon()
	{
		$this->getImgSize();
		//print_r( $this->img_info );
		if( !$this->img_info ) return false;
		$this->getNewSize();
		$this->makeIcon();
	}

	function getImgSize()
	{
		$this->img_info = @getImagesize( $this->path );
		$this->w = $this->img_info[0];
		$this->h = $this->img_info[1];

		switch ( $this->img_info[2] )
		{
			case 1:
				$this->t = 'gif';
				break;
			case 2:
				$this->t = 'jpg';
				break;
			case 3:
				$this->t = 'png';
				break;
		}
	}

	function getNewSize()
	{
		if( $this->w > $this->h )
		{
			$this->nh = $this->size;
			$this->nw = ($this->size/$this->h) * $this->w;
			$this->makeIcon( 1 );
		}
		else
		{
			$this->nw = $this->size;
			$this->nh = ($this->size/$this->w) * $this->h;
			$this->makeIcon( 0 );
		}
	}

	function makeIcon( $type = 1 )
	{
		
			//$img_des = ImageCreateTrueColor ( $this->size, $this->size );
			$img_des = ImageCreateTrueColor ( $this->size , $this->size );

			$background = imagecolorallocate( $img_des , 255 , 255 , 255 );
			imagefill( $img_des , 0 , 0 , $background ); 

			switch ( $this->t )
			{
				case 'gif':
					$img_src = ImageCreateFromGIF ( $this->path );
					break;

				case 'jpg':
					$img_src = ImageCreateFromJPEG ( $this->path );
					break;

				case 'png':
					$img_src = ImageCreateFromPNG ( $this->path );
					break;
			}
			
			if( $type == 1 )
			{
				imagecopyresampled( $img_des, $img_src, 0, 0, ($this->nw-$this->size)/2 , 0, $this->nw , $this->nh, $this->w, $this->h );
			}
			else
			{
				imagecopyresampled( $img_des, $img_src, 0, 0, 0 , ($this->nh-$this->size)/2, $this->nw , $this->nh, $this->w, $this->h );
			}
			/*
			imageline( $img_des , 0 , 0 , 0 , ($this->size-1) , imagecolorallocate( $img_des , 220 , 220 , 220 )  );
			imageline( $img_des , 0 , ($this->size-1) , ($this->size-1) , ($this->size-1) , imagecolorallocate( $img_des , 220 , 220 , 220 )  );
			imageline( $img_des , ($this->size-1) , ($this->size-1) , ($this->size-1) , 0 , imagecolorallocate( $img_des , 220 , 220 , 220 )  );
			imageline( $img_des , ($this->size-1) , 0 , 0 , 0 , imagecolorallocate( $img_des , 220 , 220 , 220 )  );
			*/
			
			//echo $this->dest ;

			switch ( $this->t )
			{
				case 'gif':
					if ( empty( $this->dest ) )
						{
						header( "Content-type: image/gif" );
						return ImageGIF( $img_des );
						}
					else
						{
						return ImageGIF( $img_des, $this->dest );
						}
						break;

				case 'jpg':
					if ( empty( $this->dest ) )
						{
						header ( "Content-type: image/jpeg" );
						return ImageJPEG( $img_des );
						}
					else
						{
						return ImageJPEG( $img_des, $this->dest );
						}
					break;

				case 'png':
					if ( empty( $this->dest ) )
						{
						header ( "Content-type: image/png" );
						return ImagePNG( $img_des );
						}
					else
						{
						return ImagePNG( $img_des, $this->dest );
						}
					break;

			}
		}

}