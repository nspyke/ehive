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

namespace EHive\Transport;

class OauthCredentials
{
    public $clientId;

    public $clientSecret;

    public $oauthToken;

    /**
     * OauthCredentials constructor.
     *
     * @param $clientId
     * @param $clientSecret
     * @param $oauthToken
     */
    public function __construct($clientId = null, $clientSecret = null, $oauthToken = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->oauthToken = $oauthToken;
    }
}
