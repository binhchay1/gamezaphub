<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\NetAppFiles;

class RegionalMigDistributionPolicy extends \Google\Collection
{
  protected $collection_key = 'zones';
  /**
   * @var int
   */
  public $targetShape;
  protected $zonesType = ZoneConfiguration::class;
  protected $zonesDataType = 'array';

  /**
   * @param int
   */
  public function setTargetShape($targetShape)
  {
    $this->targetShape = $targetShape;
  }
  /**
   * @return int
   */
  public function getTargetShape()
  {
    return $this->targetShape;
  }
  /**
   * @param ZoneConfiguration[]
   */
  public function setZones($zones)
  {
    $this->zones = $zones;
  }
  /**
   * @return ZoneConfiguration[]
   */
  public function getZones()
  {
    return $this->zones;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RegionalMigDistributionPolicy::class, 'Google_Service_NetAppFiles_RegionalMigDistributionPolicy');
