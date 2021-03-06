s, thus making them corrupt.
* Meracl ID3 Tag Writer v1.3.4 (and older) incorrectly truncates the
  last byte of data from an MP3 file when appending a new ID3v1 tag.
  (detected by getID3())
* Lossless-Audio files encoded with and without the -noseek switch
  do actually differ internally and therefore cannot match md5_data
* iTunes has been known to append a new ID3v1 tag on the end of an
  existing ID3v1 tag when ID3v2 tag is also present
  (detected by getID3())
* MediaMonkey may write a blank RGAD ID3v2 frame but put actual
  replay gain adjustments in a series of user-defined TXXX frames
  (detected and handled by getID3() since v1.9.2)




Reference material:
===========================================================================

[www.id3.org material now mirrored at http://id3lib.sourceforge.net/id3/]
* http://www.id3.org/id3v2.4.0-structure.txt
* http://www.id3.org/id3v2.4.0-frames.txt
* http://www.id3.org/id3v2.4.0-changes.txt
* http://www.id3.org/id3v2.3.0.txt
* http://www.id3.org/id3v2-00.txt
* http://www.id3.org/mp3frame.html
* http://minnie.tuhs.org/pipermail/mp3encoder/2001-January/001800.html <mathewhendry@hotmail.com>
* http://www.dv.co.yu/mpgscript/mpeghdr.htm
* http://www.mp3-tech.org/programmer/frame_header.html
* http://users.belgacom.net/gc247244/extra/tag.html
* http://gabriel.mp3-tech.org/mp3infotag.html
* http://www.id3.org/iso4217.html
* http://www.unicode.org/Public/MAPPINGS/ISO8859/8859-1.TXT
* http://www.xiph.org/ogg/vorbis/doc/framing.html
* http://www.xiph.org/ogg/vorbis/doc/v-comment.html
* http://leknor.com/code/php/class.ogg.php.txt
* http://www.id3.org/iso639-2.html
* http://www.id3.org/lyrics3.html
* http://www.id3.org/lyrics3200.html
* http://www.psc.edu/general/software/packages/ieee/ieee.html
* http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/ieee-expl.html
* http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/binary.html
* http://www.jmcgowan.com/avi.html
* http://www.wotsit.org/
* http://www.herdsoft.com/ti/davincie/davp3xo2.htm
* http://www.mathdogs.com/vorbis-illuminated/bitstream-appendix.html
* "Standard MIDI File Format" by Dustin Caldwell (from www.wotsit.org)
* http://midistudio.com/Help/GMSpecs_Patches.htm
* http://www.xiph.org/archives/vorbis/200109/0459.html
* http://www.replaygain.org/
* http://www.lossless-audio.com/
* http://download.microsoft.com/download/winmediatech40/Doc/1.0/WIN98MeXP/EN-US/ASF_Specification_v.1.0.exe
* http://mediaxw.sourceforge.net/files/doc/Active%20Streaming%20Format%20(ASF)%201.0%20Specification.pdf
* http://www.uni-jena.de/~pfk/mpp/sv8/ (archived at http://www.hydrogenaudio.org/musepack/klemm/www.personal.uni-jena.de/~pfk/mpp/sv8/)
* http://jfaul.de/atl/
* http://www.uni-jena.de/~pfk/mpp/ (archived at http://www.hydrogenaudio.org/musepack/klemm/www.personal.uni-jena.de/~pfk/mpp/)
* http://www.libpng.org/pub/png/spec/png-1.2-pdg.html
* http://www.real.com/devzone/library/creating/rmsdk/doc/rmff.htm
* http://www.fastgraph.com/help/bmp_os2_header_format.html
* http://netghost.narod.ru/gff/graphics/summary/os2bmp.htm
* http://flac.sourceforge.net/format.html
* http://www.research.att.com/projects/mpegaudio/mpeg2.html
* http://www.audiocoding.com/wiki/index.php?page=AAC
* http://libmpeg.org/mpeg4/doc/w2203tfs.pdf
* http://www.geocities.com/xhelmboyx/quicktime/formats/qtm-layout.txt
* http://developer.apple.com/techpubs/quicktime/qtdevdocs/RM/frameset.htm
* http://www.nullsoft.com/nsv/
* http://www.wotsit.org/download.asp?f=iso9660
* http://sandbox.mc.edu/~bennet/cs110/tc/tctod.html
* http://www.cdroller.com/htm/readdata.html
* http://www.speex.org/manual/node10.html
* http://www.harmony-central.com/Computer/Programming/aiff-file-format.doc
* http://www.faqs.org/rfcs/rfc2361.html
* http://ghido.shelter.ro/
* http://www.ebu.ch/tech_t3285.pdf
* http://www.sr.se/utveckling/tu/bwf
* http://ftp.aessc.org/pub/aes46-2002.pdf
* http://cartchunk.org:8080/
* http://www.broadcastpapers.com/radio/cartchunk01.htm
* http://www.hr/josip/DSP/AudioFile2.html
* http://home.attbi.com/~chris.bagwell/AudioFormats-11.html
* http://www.pure-mac.com/extkey.html
* http://cesnet.dl.sourceforge.net/sourceforge/bonkenc/bonk-binary-format-0.9.txt
* http://www.headbands.com/gspot/
* http://www.openswf.org/spec/SWFfileformat.html
* http://j-faul.virtualave.net/
* http://www.btinternet.com/~AnthonyJ/Atari/programming/avr_format.html
* http://cui.unige.ch/OSG/info/AudioFormats/ap11.html
* http://sswf.sourceforge.net/SWFalexref.html
* http://www.geocities.com/xhelmboyx/quicktime/formats/qti-layout.txt
* http://www-lehre.informatik.uni-osnabrueck.de/~fbstark/diplom/docs/swf/Flash_Uncovered.htm
* http://developer.apple.com/quicktime/icefloe/dispatch012.html
* http://www.csdn.net/Dev/Format/graphics/PCD.htm
* http://tta.iszf.irk.ru/
* http://www.atsc.org/standards/a_52a.pdf
* http://www.alanwood.net/unicode/
* http://www.freelists.org/archives/matroska-devel/07-2003/msg00010.html
* http://www.its.msstate.edu/net/real/reports/config/tags.stats
* http://homepages.slingshot.co.nz/~helmboy/quicktime/formats/qtm-layout.txt
* http://brennan.young.net/Comp/LiveStage/things.html
* http://www.multiweb.cz/twoinches/MP3inside.htm
* http://www.geocities.co.jp/SiliconValley-Oakland/3664/alittle.html#GenreExtended
* http://www.mactech.com/articles/mactech/Vol.06/06.01/SANENormalized/
* http://www.unicode.org/unicode/faq/utf_bom.html
* http://tta.corecodec.org/?menu=format
* http://www.scvi.net/nsvformat.htm
* http://pda.etsi.org/pda/queryform.asp
* http://cpansearch.perl.org/src/RGIBSON/Audio-DSS-0.02/lib/Audio/DSS.pm
* http://trac.musepack.net/trac/wiki/SV8Specification
* http://wyday.com/cuesharp/specification.php
* http://www.sno.phy.queensu.ca/~phil/exiftool/TagNames/Nikon.html                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
//          also https://github.com/JamesHeinrich/getID3       //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.tag.apetag.php                                       //
// module for analyzing APE tags                               //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////

class getid3_apetag extends getid3_handler
{
	public $inline_attachments = true; // true: return full data for all attachments; false: return no data for all attachments; integer: return data for attachments <= than this; string: save as file to this directory
	public $overrideendoffset  = 0;

	public function Analyze() {
		$info = &$this->getid3->info;

		if (!getid3_lib::intValueSupported($info['filesize'])) {
			$info['warning'][] = 'Unable to check for APEtags because file is larger than '.round(PHP_INT_MAX / 1073741824).'GB';
			return false;
		}

		$id3v1tagsize     = 128;
		$apetagheadersize = 32;
		$lyrics3tagsize   = 10;

		if ($this->overrideendoffset == 0) {

			$this->fseek(0 - $id3v1tagsize - $apetagheadersize - $lyrics3tagsize, SEEK_END);
			$APEfooterID3v1 = $this->fread($id3v1tagsize + $apetagheadersize + $lyrics3tagsize);

			//if (preg_match('/APETAGEX.{24}TAG.{125}$/i', $APEfooterID3v1)) {
			if (substr($APEfooterID3v1, strlen($APEfooterID3v1) - $id3v1tagsize - $apetagheadersize, 8) == 'APETAGEX') {

				// APE tag found before ID3v1
				$info['ape']['tag_offset_end'] = $info['filesize'] - $id3v1tagsize;

			//} elseif (preg_match('/APETAGEX.{24}$/i', $APEfooterID3v1)) {
			} elseif (substr($APEfooterID3v1, strlen($APEfooterID3v1) - $apetagheadersize, 8) == 'APETAGEX') {

				// APE tag found, no ID3v1
				$info['ape']['tag_offset_end'] = $info['filesize'];

			}

		} else {

			$this->fseek($this->overrideendoffset - $apetagheadersize);
			if ($this->fread(8) == 'APETAGEX') {
				$info['ape']['tag_offset_end'] = $this->overrideendoffset;
			}

		}
		if (!isset($info['ape']['tag_offset_end'])) {

			// APE tag not found
			unset($info['ape']);
			return false;

		}

		// shortcut
		$thisfile_ape = &$info['ape'];

		$this->fseek($thisfile_ape['tag_offset_end'] - $apetagheadersize);
		$APEfooterData = $this->fread(32);
		if (!($thisfile_ape['footer'] = $this->parseAPEheaderFooter($APEfooterData))) {
			$info['error'][] = 'Error parsing APE footer at offset '.$thisfile_ape['tag_offset_end'];
			return false;
		}

		if (isset($thisfile_ape['footer']['flags']['header']) && $thisfile_ape['footer']['flags']['header']) {
			$this->fseek($thisfile_ape['tag_offset_end'] - $thisfile_ape['footer']['raw']['tagsize'] - $apetagheadersize);
			$thisfile_ape['tag_offset_start'] = $this->ftell();
			$APEtagData = $this->fread($thisfile_ape['footer']['raw']['tagsize'] + $apetagheadersize);
		} else {
			$thisfile_ape['tag_offset_start'] = $thisfile_ape['tag_offset_end'] - $thisfile_ape['footer']['raw']['tagsize'];
			$this->fseek($thisfile_ape['tag_offset_start']);
			$APEtagData = $this->fread($thisfile_ape['footer']['raw']['tagsize']);
		}
		$info['avdataend'] = $thisfile_ape['tag_offset_start'];

		if (isset($info['id3v1']['tag_offset_start']) && ($info['id3v1']['tag_offset_start'] < $thisfile_ape['tag_offset_end'])) {
			$info['warning'][] = 'ID3v1 tag information ignored since it appears to be a false synch in APEtag data';
			unset($info['id3v1']);
			foreach ($info['warning'] as $key => $value) {
				if ($value == 'Some ID3v1 fields do not use NULL characters for padding') {
					unset($info['warning'][$key]);
					sort($info['warning']);
					break;
				}
			}
		}

		$offset = 0;
		if (isset($thisfile_ape['footer']['flags']['header']) && $thisfile_ape['footer']['flags']['header']) {
			if ($thisfile_ape['header'] = $this->parseAPEheaderFooter(substr($APEtagData, 0, $apetagheadersize))) {
				$offset += $apetagheadersize;
			} else {
				$info['error'][] = 'Error parsing APE header at offset '.$thisfile_ape['tag_offset_start'];
				return false;
			}
		}

		// shortcut
		$info['replay_gain'] = array();
		$thisfile_replaygain = &$info['replay_gain'];

		for ($i = 0; $i < $thisfile_ape['footer']['raw']['tag_items']; $i++) {
			$value_size = getid3_lib::LittleEndian2Int(substr($APEtagData, $offset, 4));
			$offset += 4;
			$item_flags = getid3_lib::LittleEndian2Int(substr($APEtagData, $offset, 4));
			$offset += 4;
			if (strstr(substr($APEtagData, $offset), "\x00") === false) {
				$info['error'][] = 'Cannot find null-byte (0x00) seperator between ItemKey #'.$i.' and value. ItemKey starts '.$offset.' bytes into the APE tag, at file offset '.($thisfile_ape['tag_offset_start'] + $offset);
				return false;
			}
			$ItemKeyLength = strpos($APEtagData, "\x00", $offset) - $offset;
			$item_key      = strtolower(substr($APEtagData, $offset, $ItemKeyLength));

			// shortcut
			$thisfile_ape['items'][$item_key] = array();
			$thisfile_ape_items_current = &$thisfile_ape['items'][$item_key];

			$thisfile_ape_items_current['offset'] = $thisfile_ape['tag_offset_start'] + $offset;

			$offset += ($ItemKeyLength + 1); // skip 0x00 terminator
			$thisfile_ape_items_current['data'] = substr($APEtagData, $offset, $value_size);
			$offset += $value_size;

			$thisfile_ape_items_current['flags'] = $this->parseAPEtagFlags($item_flags);
			switch ($thisfile_ape_items_current['flags']['item_contents_raw']) {
				case 0: // UTF-8
				case 2: // Locator (URL, filename, etc), UTF-8 encoded
					$thisfile_ape_items_current['data'] = explode("\x00", $thisfile_ape_items_current['data']);
					break;

				case 1:  // binary data
				default:
					break;
			}

			switch (strtolower($item_key)) {
				// http://wiki.hydrogenaud.io/index.php?title=ReplayGain#MP3Gain
				case 'replaygain_track_gain':
					if (preg_match('#^[\\-\\+][0-9\\.,]{8}$#', $thisfile_ape_items_current['data'][0])) {
						$thisfile_replaygain['track']['adjustment'] = (float) str_replace(',', '.', $thisfile_ape_items_current['data'][0]); // float casting will see "0,95" as zero!
						$thisfile_replaygain['track']['originator'] = 'unspecified';
					} else {
						$info['warning'][] = 'MP3gainTrackGain value in APEtag appears invalid: "'.$thisfile_ape_items_current['data'][0].'"';
					}
					break;

				case 'replaygain_track_peak':
					if (preg_match('#^[0-9\\.,]{8}$#', $thisfile_ape_items_current['data'][0])) {
						$thisfile_replaygain['track']['peak']       = (float) str_replace(',', '.', $thisfile_ape_items_current['data'][0]); // float casting will see "0,95" as zero!
						$thisfile_replaygain['track']['originator'] = 'unspecified';
						if ($thisfile_replaygain['track']['peak'] <= 0) {
							$info['warning'][] = 'ReplayGain Track peak from APEtag appears invalid: '.$thisfile_replaygain['track']['peak'].' (original value = "'.$thisfile_ape_items_current['data'][0].'")';
						}
					} else {
						$info['warning'][] = 'MP3gainTrackPeak value in APEtag appears invalid: "'.$thisfile_ape_items_current['data'][0].'"';
					}
					break;

				case 'replaygain_album_gain':
					if (preg_match('#^[\\-\\+][0-9\\.,]{8}$#', $thisfile_ape_items_current['data'][0])) {
						$thisfile_replaygain['album']['adjustment'] = (float) str_replace(',', '.', $thisfile_ape_items_current['data'][0]); // float casting will see "0,95" as zero!
						$thisfile_replaygain['album']['originator'] = 'unspecified';
					} else {
						$info['warning'][] = 'MP3gainAlbumGain value in APEtag appears invalid: "'.$thisfile_ape_items_current['data'][0].'"';
					}
					break;

				case 'replaygain_album_peak':
					if (preg_match('#^[0-9\\.,]{8}$#', $thisfile_ape_items_current['data'][0])) {
						$thisfile_replaygain['album']['peak']       = (float) str_replace(',', '.', $thisfile_ape_items_current['data'][0]); // float casting will see "0,95" as zero!
						$thisfile_replaygain['album']['originator'] = 'unspecified';
						if ($thisfile_replaygain['album']['peak'] <= 0) {
							$info['warning'][] = 'ReplayGain Album peak from APEtag appears invalid: '.$thisfile_replaygain['album']['peak'].' (original value = "'.$thisfile_ape_items_current['data'][0].'")';
						}
					} else {
						$info['warning'][] = 'MP3gainAlbumPeak value in APEtag appears invalid: "'.$thisfile_ape_items_current['data'][0].'"';
					}
					break;

				case 'mp3gain_undo':
					if (preg_match('#^[\\-\\+][0-9]{3},[\\-\\+][0-9]{3},[NW]$#', $thisfile_ape_items_current['data'][0])) {
						list($mp3gain_undo_left, $mp3gain_undo_right, $mp3gain_undo_wrap) = explode(',', $thisfile_ape_items_current['data'][0]);
						$thisfile_replaygain['mp3gain']['undo_left']  = intval($mp3gain_undo_left);
						$thisfile_replaygain['mp3gain']['undo_right'] = intval($mp3gain_undo_right);
						$thisfile_replaygain['mp3gain']['undo_wrap']  = (($mp3gain_undo_wrap == 'Y') ? true : false);
					} else {
						$info['warning'][] = 'MP3gainUndo value in APEtag appears invalid: "'.$thisfile_ape_items_current['data'][0].'"';
					}
					break;

				case 'mp3gain_minmax':
					if (preg_match('#^[0-9]{3},[0-9]{3}$#', $thisfile_ape_items_current['data'][0])) {
						list($mp3gain_globalgain_min, $mp3gain_globalgain_max) = explode(',', $thisfile_ape_items_current['data'][0]);
						$thisfile_replaygain['mp3gain']['globalgain_track_min'] = intval($mp3gain_globalgain_min);
						$thisfile_replaygain['mp3gain']['globalgain_track_max'] = intval($mp3gain_globalgain_max);
					} else {
						$info['warning'][] = 'MP3gainMinMax value in APEtag appears invalid: "'.$thisfile_ape_items_current['data'][0].'"';
					}
					break;

				case 'mp3gain_album_minmax':
					if (preg_match('#^[0-9]{3},[0-9]{3}$#', $thisfile_ape_items_current['data'][0])) {
						list($mp3gain_globalgain_album_min, $mp3gain_globalgain_album_max) = explode(',', $thisfile_ape_items_current['data'][0]);
						$thisfile_replaygain['mp3gain']['globalgain_album_min'] = intval($mp3gain_globalgain_album_min);
						$thisfile_replaygain['mp3gain']['globalgain_album_max'] = intval($mp3gain_globalgain_album_max);
					} else {
						$info['warning'][] = 'MP3gainAlbumMinMax value in APEtag appears invalid: "'.$thisfile_ape_items_current['data'][0].'"';
					}
					break;

				case 'tracknumber':
					if (is_array($thisfile_ape_items_current['data'])) {
						foreach ($thisfile_ape_items_current['data'] as $comment) {
							$thisfile_ape['comments']['track'][] = $comment;
						}
					}
					break;

				case 'cover art (artist)':
				case 'cover art (back)':
				case 'cover art (band logo)':
				case 'cover art (band)':
				case 'cover art (colored fish)':
				case 'cover art (composer)':
				case 'cover art (conductor)':
				case 'cover art (front)':
				case 'cover art (icon)':
				case 'cover art (illustration)':
				case 'cover art (lead)':
				case 'cover art (leaflet)':
				case 'cover art (lyricist)':
				case 'cover art (media)':
				case 'cover art (movie scene)':
				case 'cover art (other icon)':
				case 'cover art (other)':
				case 'cover art (performance)':
				case 'cover art (publisher logo)':
				case 'cover art (recording)':
				case 'cover art (studio)':
					// list of possible cover arts from http://taglib-sharp.sourcearchive.com/documentation/2.0.3.0-2/Ape_2Tag_8cs-source.html
					if (is_array($thisfile_ape_items_current['data'])) {
						$info['warning'][] = 'APEtag "'.$item_key.'" should be flagged as Binary data, but was incorrectly flagged as UTF-8';
						$thisfile_ape_items_current['data'] = implode("\x00", $thisfile_ape_items_current['data']);
					}
					list($thisfile_ape_items_current['filename'], $thisfile_ape_items_current['data']) = explode("\x00", $thisfile_ape_items_current['data'], 2);
					$thisfile_ape_items_current['data_offset'] = $thisfile_ape_items_current['offset'] + strlen($thisfile_ape_items_current['filename']."\x00");
					$thisfile_ape_items_current['data_length'] = strlen($thisfile_ape_items_current['data']);

					$thisfile_ape_items_current['image_mime'] = '';
					$imageinfo = array();
					$imagechunkcheck = getid3_lib::GetDataImageSize($thisfile_ape_items_current['data'], $imageinfo);
					$thisfile_ape_items_current['image_mime'] = image_type_to_mime_type($imagechunkcheck[2]);

					do {
						if ($this->inline_attachments === false) {
							// skip entirely
							unset($thisfile_ape_items_current['data']);
							break;
						}
						if ($this->inline_attachments === true) {
							// great
						} elseif (is_int($this->inline_attachments)) {
							if ($this->inline_attachments < $thisfile_ape_items_current['data_length']) {
								// too big, skip
								$info['warning'][] = 'attachment at '.$thisfile_ape_items_current['offset'].' is too large to process inline ('.number_format($thisfile_ape_items_current['data_length']).' bytes)';
								unset($thisfile_ape_items_current['data']);
								break;
							}
						} elseif (is_string($this->inline_attachments)) {
							$this->inline_attachments = rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $this->inline_attachments), DIRECTORY_SEPARATOR);
							if (!is_dir($this->inline_attachments) || !is_writable($this->inline_attachments)) {
								// cannot write, skip
								$info['warning'][] = 'attachment at '.$thisfile_ape_items_current['offset'].' cannot be saved to "'.$this->inline_attachments.'" (not writable)';
								unset($thisfile_ape_items_current['data']);
								break;
							}
						}
						// if we get this far, must be OK
						if (is_string($this->inline_attachments)) {
							$destination_filename = $this->inline_attachments.DIRECTORY_SEPARATOR.md5($info['filenamepath']).'_'.$thisfile_ape_items_current['data_offset'];
							if (!file_exists($destination_filename) || is_writable($destination_filename)) {
								file_put_contents($destination_filename, $thisfile_ape_items_current['data']);
							} else {
								$info['warning'][] = 'attachment at '.$thisfile_ape_items_current['offset'].' cannot be saved to "'.$destination_filename.'" (not writable)';
							}
							$thisfile_ape_items_current['data_filename'] = $destination_filename;
							unset($thisfile_ape_items_current['data']);
						} else {
							if (!isset($info['ape']['comments']['picture'])) {
								$info['ape']['comments']['picture'] = array();
							}
							$comments_picture_data = array();
							foreach (array('data', 'image_mime', 'image_width', 'image_height', 'imagetype', 'picturetype', 'description', 'datalength') as $picture_key) {
								if (isset($thisfile_ape_items_current[$picture_key])) {
									$comments_picture_data[$picture_key] = $thisfile_ape_items_current[$picture_key];
								}
							}
							$info['ape']['comments']['picture'][] = $comments_picture_data;
							unset($comments_picture_data);
						}
					} while (false);
					break;

				default:
					if (is_array($thisfile_ape_items_current['data'])) {
						foreach ($thisfile_ape_items_current['data'] as $comment) {
							$thisfile_ape['comments'][strtolower($item_key)][] = $comment;
						}
					}
					break;
			}

		}
		if (empty($thisfile_replaygain)) {
			unset($info['replay_gain']);
		}
		return true;
	}

	public function parseAPEheaderFooter($APEheaderFooterData) {
		// http://www.uni-jena.de/~pfk/mpp/sv8/apeheader.html

		// shortcut
		$headerfooterinfo['raw'] = array();
		$headerfooterinfo_raw = &$headerfooterinfo['raw'];

		$headerfooterinfo_raw['footer_tag']   =                  substr($APEheaderFooterData,  0, 8);
		if ($headerfooterinfo_raw['footer_tag'] != 'APETAGEX') {
			return false;
		}
		$headerfooterinfo_raw['version']      = getid3_lib::LittleEndian2Int(substr($APEheaderFooterData,  8, 4));
		$headerfooterinfo_raw['tagsize']      = getid3_lib::LittleEndian2Int(substr($APEheaderFooterData, 12, 4));
		$headerfooterinfo_raw['tag_items']    = getid3_lib::LittleEndian2Int(substr($APEheaderFooterData, 16, 4));
		$headerfooterinfo_raw['global_flags'] = getid3_lib::LittleEndian2Int(substr($APEheaderFooterData, 20, 4));
		$headerfooterinfo_raw['reserved']     =                              substr($APEheaderFooterData, 24, 8);

		$headerfooterinfo['tag_version']         = $headerfooterinfo_raw['version'] / 1000;
		if ($headerfooterinfo['tag_version'] >= 2) {
			$headerfooterinfo['flags'] = $this->parseAPEtagFlags($headerfooterinfo_raw['global_flags']);
		}
		return $headerfooterinfo;
	}

	public function parseAPEtagFlags($rawflagint) {
		// "Note: APE Tags 1.0 do not use any of the APE Tag flags.
		// All are set to zero on creation and ignored on reading."
		// http://wiki.hydrogenaud.io/index.php?title=Ape_Tags_Flags
		$flags['header']            = (bool) ($rawflagint & 0x80000000);
		$flags['footer']            = (bool) ($rawflagint & 0x40000000);
		$flags['this_is_header']    = (bool) ($rawflagint & 0x20000000);
		$flags['item_contents_raw'] =        ($rawflagint & 0x00000006) >> 1;
		$flags['read_only']         = (bool) ($rawflagint & 0x00000001);

		$flags['item_contents']     = $this->APEcontentTypeFlagLookup($flags['item_contents_raw']);

		return $flags;
	}

	public function APEcontentTypeFlagLookup($contenttypeid) {
		static $APEcontentTypeFlagLookup = array(
			0 => 'utf-8',
			1 => 'binary',
			2 => 'external',
			3 => 'reserved'
		);
		return (isset($APEcontentTypeFlagLookup[$contenttypeid]) ? $APEcontentTypeFlagLookup[$contenttypeid] : 'invalid');
	}

	public function APEtagItemIsUTF8Lookup($itemkey) {
		static $APEtagItemIsUTF8Lookup = array(
			'title',
			'subtitle',
			'artist',
			'album',
			'debut album',
			'publisher',
			'conductor',
			'track',
			'composer',
			'comment',
			'copyright',
			'publicationright',
			'file',
			'year',
			'record date',
			'record location',
			'genre',
			'media',
			'related',
			'isrc',
			'abstract',
			'language',
			'bibliography'
		);
		return in_array(strtolower($itemkey), $APEtagItemIsUTF8Lookup);
	}

}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
//          also https://github.com/JamesHeinrich/getID3       //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio.flac.php                                       //
// module for analyzing FLAC and OggFLAC audio files           //
// dependencies: module.audio.ogg.php                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'module.audio.ogg.php', __FILE__, true);

/**
* @tutorial http://flac.sourceforge.net/format.html
*/
class getid3_flac extends getid3_handler
{
	const syncword = 'fLaC';

	public function Analyze() {
		$info = &$this->getid3->info;

		$this->fseek($info['avdataoffset']);
		$StreamMarker = $this->fread(4);
		if ($StreamMarker != self::syncword) {
			return $this->error('Expecting "'.getid3_lib::PrintHexBytes(self::syncword).'" at offset '.$info['avdataoffset'].', found "'.getid3_lib::PrintHexBytes($StreamMarker).'"');
		}
		$info['fileformat']            = 'flac';
		$info['audio']['dataformat']   = 'flac';
		$info['audio']['bitrate_mode'] = 'vbr';
		$info['audio']['lossless']     = true;

		// parse flac container
		return $this->parseMETAdata();
	}

	public function parseMETAdata() {
		$info = &$this->getid3->info;
		do {
			$BlockOffset   = $this->ftell();
			$BlockHeader   = $this->fread(4);
			$LBFBT         = getid3_lib::BigEndian2Int(substr($BlockHeader, 0, 1));
			$LastBlockFlag = (bool) ($LBFBT & 0x80);
			$BlockType     =        ($LBFBT & 0x7F);
			$BlockLength   = getid3_lib::BigEndian2Int(substr($BlockHeader, 1, 3));
			$BlockTypeText = self::metaBlockTypeLookup($BlockType);

			if (($BlockOffset + 4 + $BlockLength) > $info['avdataend']) {
				$this->error('METADATA_BLOCK_HEADER.BLOCK_TYPE ('.$BlockTypeText.') at offset '.$BlockOffset.' extends beyond end of file');
				break;
			}
			if ($BlockLength < 1) {
				$this->error('METADATA_BLOCK_HEADER.BLOCK_LENGTH ('.$BlockLength.') at offset '.$BlockOffset.' is invalid');
				break;
			}

			$info['flac'][$BlockTypeText]['raw'] = array();
			$BlockTypeText_raw = &$info['flac'][$BlockTypeText]['raw'];

			$BlockTypeText_raw['offset']          = $BlockOffset;
			$BlockTypeText_raw['last_meta_block'] = $LastBlockFlag;
			$BlockTypeText_raw['block_type']      = $BlockType;
			$BlockTypeText_raw['block_type_text'] = $BlockTypeText;
			$BlockTypeText_raw['block_length']    =