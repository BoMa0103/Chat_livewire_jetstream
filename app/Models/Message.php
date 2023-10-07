<?php

namespace App\Models;

use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property string $content
 * @property string $user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereFromUser($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereSendTime($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @method static Builder|Message whereValue($value)
 * @property int $user_id
 * @property-read User|null $company
 * @method static MessageFactory factory($count = null, $state = [])
 * @method static Builder|Message whereUserId($value)
 */
class Message extends Model
{
    use HasFactory;

    public const UNREAD_STATUS = 0;
    public const READ_STATUS = 1;

    protected static $unguarded = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
