<?php
/**
 * This file is part of the nspyke/ehive library.
 *
 * Copyright (c) 2017. Nik Spijkerman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 * Copyright (C) 2012 Vernon Systems Limited
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions
 * of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace EHive\Domain\Community;

use EHive\Domain\ObjectRecord\MediaSet;

class Community
{

    public $communityId = 0;
    public $name = "";
    public $description = "";
    public $searchScore = 0;
    public $mediaSets = [];

    public function __construct($json = null)
    {
        if (isset($json)) {
            $this->communityId = isset($json->communityId) ? $json->communityId : 0;
            $this->name = isset($json->name) ? $json->name : "";
            $this->description = isset($json->description) ? $json->description : "";
            $this->searchScore = isset($json->searchScore) ? $json->searchScore : 0;

            if (isset($json->mediaSets)) {
                foreach ($json->mediaSets as $mediaSetJson) {
                    $mediaSet = new MediaSet($mediaSetJson);

                    $this->mediaSets[$mediaSet->identifier] = $mediaSet;
                }
            }
        }
    }

    public function getMediaSetByIdentifier($mediaSetIdentifier)
    {
        if (isset($this->mediaSets) and array_key_exists($mediaSetIdentifier, $this->mediaSets)) {
            return $this->mediaSets[$mediaSetIdentifier];
        }

        return null;
    }
}
