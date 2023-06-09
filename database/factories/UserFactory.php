<?php

namespace Database\Factories;

use App\Models\Character;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemPrototype;
use App\Models\Location;
use App\Modules\Character\Application\Contracts\CharacterRepositoryInterface;
use App\Modules\Character\Domain\CharacterType;
use App\Modules\Character\Domain\HitPoints;
use App\Modules\Character\Infrastructure\Repositories\RaceRepository;
use App\Modules\Equipment\Application\Contracts\InventoryRepositoryInterface;
use App\Modules\Equipment\Application\Contracts\ItemRepositoryInterface;
use App\Modules\Equipment\Domain\ItemStatus;
use App\Modules\Trade\Application\Contracts\StoreRepositoryInterface;
use App\Models\Race;
use App\Models\Store;
use App\Models\User;
use App\Modules\Trade\Domain\StoreType;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;
use Faker\Generator;

/** @var Factory $factory */

$factory->define(User::class, static function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(60)
    ];
});

$factory->define(Character::class, static function (Generator $faker) {

    /** @var CharacterRepositoryInterface $characterRepository */
    $characterRepository = resolve(CharacterRepositoryInterface::class);

    /** @var Race $raceModel */
    $raceModel = Race::query()->inRandomOrder()->first();
    $location = Location::query()->inRandomOrder()->first();

    $race = (new RaceRepository())->getOne($raceModel->getId());
    $hitPoints = HitPoints::byRace($race);

    $genders = ['male', 'female'];
    $types = CharacterType::TYPES;

    $characterId = $characterRepository->nextIdentity()->toString();

    return [
        'id' => $characterId,

        'race_id' => $race->getId(),

        'level_id' => 1,

        'location_id' => $location,

        'name' => $faker->name,
        'gender' => $genders[array_rand($genders)],
        'type' => $characterType = $types[array_rand($types)],

        'xp' => 0,
        'reputation' => 0,

        // attributes
        'strength' => $race->getStrength(),
        'agility' => $race->getAgility(),
        'constitution' => $race->getConstitution(),
        'intelligence' => $race->getIntelligence(),
        'charisma' => $race->getCharisma(),

        'hit_points' => $hitPoints->getCurrentHitPoints(),
        'total_hit_points' => $hitPoints->getMaximumHitPoints(),

        'user_id' => static function () use ($characterType) {
            return $characterType === CharacterType::PLAYER
                ? factory(User::class)->create()->id
                : null;
        },
    ];
});

$factory->afterCreating(Character::class, static function (Character $character) {

    /** @var InventoryRepositoryInterface $inventoryRepository */
    $inventoryRepository = resolve(InventoryRepositoryInterface::class);

    /** @var StoreRepositoryInterface $storeRepository */
    $storeRepository = resolve(StoreRepositoryInterface::class);

    Inventory::query()->create([
        'id' => $inventoryRepository->nextIdentity()->toString(),
        'character_id' => $character->getId(),
        'money' => random_int(0, 5000),
    ]);

    Store::query()->create([
        'id' => $storeRepository->nextIdentity()->toString(),
        'character_id' => $character->getId(),
        'money' => random_int(0, 5000),
        'type' => $character->isMerchant() ? StoreType::BUY_AND_SELL : StoreType::SELL_ONLY,
    ]);
});


$factory->define(Item::class, static function () {
    static $charactersIds = [];

    /** @var ItemRepositoryInterface $itemRepository */
    $itemRepository = resolve(ItemRepositoryInterface::class);

    /** @var ItemPrototype $itemPrototype */
    $itemPrototype = ItemPrototype::query()->inRandomOrder()->first();

    /** @var Character $character */
    $character = Character::query()->whereNotIn('id', $charactersIds)->first();

    $charactersIds[] = $character->getId();

    $itemId = $itemRepository->nextIdentity()->toString();

    return [
        'id' => $itemId,
        'name' => $itemPrototype->getName(),
        'description' => $itemPrototype->getDescription(),
        'effects' => $itemPrototype->getEffects(),
        'price' => $itemPrototype->getPrice(),
        'type' => $itemPrototype->getType(),
        'image_file_path' => $itemPrototype->getImageFilePath(),
        'prototype_id' => $itemPrototype->getId(),
        'creator_character_id' => $character->getId(),
    ];
});

$factory->afterCreating(Item::class, static function (Item $item) {

    static $charactersIds = [];

    /** @var Character $character */
    $character = Character::query()->whereNotIn('id', $charactersIds)->first();

    $charactersIds[] = $character->getId();

    $character->inventory->items()->attach($item->getId(), [
        'inventory_slot_number' => 0,
        'status' => ItemStatus::EQUIPPED,
    ]);
});

$factory->afterCreating(Item::class, static function (Item $item) {

    static $charactersIds = [];

    /** @var Character $character */
    $character = Character::query()->whereNotIn('id', $charactersIds)->first();

    $charactersIds[] = $character->getId();

    $character->store->items()->attach($item->getId(), [
        'inventory_slot_number' => 0,
    ]);
});
