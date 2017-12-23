<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 15:19
 */

namespace Jetifier\Payload;

/**
 * @link https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support
 */
class Notification implements \JsonSerializable
{
    private $data = [
        'title' => null,
        'body' => null,
        'sound' => null,
        'badge' => null,
        'click_action' => null,
        'icon' => null,
        'color' => null,
        'tag' => null
    ];


    /**
     * Notification constructor.
     * @param string $title The notification's title.  This field is not visible on iOS phones and tablets.
     */
    public function __construct(?string $title = null)
    {
        $this->data['title'] = $title;
    }


    /**
     * @param $name
     * @param $value
     * @throws \RuntimeException
     */
    public function __set($name, $value)
    {
        throw new \RuntimeException('Cannot add custom param to notification');
    }

    /**
     * The notification's title text.
     * devices: All
     * @param string $title
     * @return Notification
     */
    public function setTitle(string $title): Notification
    {
        $this->data['title'] = $title;
        return $this;
    }

    /**
     * The notification's body text.
     * devices: All
     * @param string $body
     * @return Notification
     */
    public function setBody(string $body): Notification
    {
        $this->data['body'] = $body;
        return $this;
    }

    /**
     * The sound to play when the device receives the notification.
     * devices: IOS, Android
     * @param string $sound
     * @return Notification
     */
    public function setSound(string $sound): Notification
    {
        $this->data['sound'] = $sound;
        return $this;
    }

    /**
     * The value of the badge on the home screen app icon.
     *
     * If not specified, the badge is not changed.
     *
     * If set to 0, the badge is removed.
     *
     * devices: IOS
     *
     * @param int $badge
     * @return Notification
     * @throws \InvalidArgumentException
     */
    public function setBadge(int $badge): Notification
    {
        $this->checkBadge($badge);
        $this->data['badge'] = $badge;
        return $this;
    }

    /**
     * @param int $badge
     * @throws \InvalidArgumentException
     */
    private function checkBadge(int $badge)
    {
        if ($badge < 0) {
            throw new \InvalidArgumentException('Badge must by minimum 0');
        }
    }

    /**
     * The action associated with a user click on the notification.
     *
     * devices: All
     * @param string $clickAction
     * @return Notification
     */
    public function setClickAction(string $clickAction): Notification
    {
        $this->data['click_action'] = $clickAction;
        return $this;
    }

    /**
     * The notification's icon.
     *
     * Sets the notification icon to myicon for drawable resource myicon.
     * If you don't send this key in the request,
     * FCM displays the launcher icon specified in your app manifest.
     *
     * devices: Android, Web
     * @param mixed $icon
     * @return Notification
     */
    public function setIcon(string $icon): Notification
    {
        $this->data['icon'] = $icon;
        return $this;
    }

    /**
     * The notification's icon color, expressed in #rrggbb format.
     *
     * devices: Android
     * @param mixed $color
     * @return Notification
     * @throws \InvalidArgumentException
     */
    public function setColor(string $color): Notification
    {
        $this->checkColor($color);
        $this->data['color'] = $color;
        return $this;
    }

    /**
     * @param string $color
     * @throws \InvalidArgumentException
     */
    private function checkColor(string $color)
    {
        $regex = '/^#[\dA-Fa-f]{6}$/';

        if (empty(preg_match($regex, $color))) {
            throw new \InvalidArgumentException('Color must be in #rrggbb format');
        }
    }

    /**
     * Identifier used to replace existing notifications in the notification drawer.
     *
     * If not specified, each request creates a new notification.
     *
     * If specified and a notification with the same tag is already being shown,
     * the new notification replaces the existing one in the notification drawer.
     *
     * devices: Android
     * @param mixed $tag
     * @return Notification
     */
    public function setTag(string $tag): Notification
    {
        $this->data['tag'] = $tag;
        return $this;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->getFilteredData();
    }

    private function getFilteredData()
    {
        return array_filter($this->data, function ($value) {
            return $value !== null;
        });
    }
}