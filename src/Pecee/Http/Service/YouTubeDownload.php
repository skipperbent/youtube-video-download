<?php
namespace Pecee\Http\Service;

class YouTubeDownload {

    const SERVICE_URL = 'http://www.youtube.com/get_video_info?&video_id=%s&asv=3&el=detailpage&hl=en_US';

    protected $videoId;

    public function __construct($videoId) {
        $this->videoId = $videoId;
    }

    public function getInfo($videoId) {
        $response = file_get_contents(sprintf(self::SERVICE_URL, $videoId));

        if(!$response) {
            throw new \ErrorException('Failed to fetch YouTube video');
        }

        parse_str($response, $out);
        if(isset($out['url_encoded_fmt_stream_map'])) {

            $videos = explode(',',$out['url_encoded_fmt_stream_map']);
            $out = array();

            foreach($videos as $video) {
                parse_str($video, $format);
                $v = new \stdClass();
                $v->quality = $format['quality'];
                $v->url = $format['url'];
                $v->type = $format['type'];

                $out[] = $v;
            }

            return $out;
        }

        return null;
    }

    public function download() {
        return $this->getInfo($this->videoId);
    }
}
