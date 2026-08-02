<?php

Broadcast::channel('cars.{siteId}', fn() => true);
