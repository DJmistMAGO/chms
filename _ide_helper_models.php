<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $reference_number
 * @property int|null $room_id
 * @property string $room_type
 * @property string $floor_level
 * @property string $ambiance
 * @property string $food_package
 * @property \Illuminate\Support\Carbon $check_in
 * @property \Illuminate\Support\Carbon $check_out
 * @property int $number_of_guests
 * @property numeric $room_price
 * @property numeric $micro_pricing_amount
 * @property numeric $total_price
 * @property string $status
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon $expires_at
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string|null $special_requests
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $check_in_date
 * @property-read mixed $check_out_date
 * @property-read \App\Models\Room|null $room
 * @property-read \App\Models\User $user
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereAmbiance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCheckIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCheckOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereFloorLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereFoodPackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereMicroPricingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereNumberOfGuests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRoomPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRoomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereSpecialRequests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereVerifiedBy($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $valid_id_status
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification whereValidIdStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IdVerification whereVerifiedBy($value)
 */
	class IdVerification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $room_type_id
 * @property string $pricing_type
 * @property string $adjustment_type
 * @property numeric $adjustment_value
 * @property string $start_date
 * @property string $end_date
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RoomType $roomType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereAdjustmentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereAdjustmentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule wherePricingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereRoomTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PricingRule whereUpdatedAt($value)
 */
	class PricingRule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $room_no
 * @property string $room_type
 * @property int $floor
 * @property numeric $base_price
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \App\Models\Booking|null $currentBooking
 * @property-read \App\Models\RoomType|null $roomType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereBasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRoomNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRoomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereUpdatedAt($value)
 */
	class Room extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Room|null $room
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomImage query()
 */
	class RoomImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property numeric $base_price
 * @property int $max_capacity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms
 * @property-read int|null $rooms_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereBasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereMaxCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereUpdatedAt($value)
 */
	class RoomType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $avatar
 * @property string|null $valid_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $google_id
 * @property string $status
 * @property int $is_google_user
 * @property int $has_changed_password
 * @property \Illuminate\Support\Carbon|null $first_google_login_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read mixed $initials
 * @property-read \App\Models\IdVerification|null $idVerification
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $teams
 * @property-read int|null $teams_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstGoogleLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereHasChangedPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsGoogleUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereValidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, ?string $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTeam($teams)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $room_id
 * @property string $reference_number
 * @property string $fullname
 * @property string $phone_number
 * @property string $ambiance
 * @property string $food_package
 * @property \Illuminate\Support\Carbon $check_in
 * @property \Illuminate\Support\Carbon $check_out
 * @property int $number_of_guests
 * @property numeric $room_price
 * @property numeric $micro_pricing_amount
 * @property numeric $total_price
 * @property string $status
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Room $room
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereAmbiance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereCheckIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereCheckOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereFoodPackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereMicroPricingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereNumberOfGuests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereRoomPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WalkInBooking whereUpdatedAt($value)
 */
	class WalkInBooking extends \Eloquent {}
}

