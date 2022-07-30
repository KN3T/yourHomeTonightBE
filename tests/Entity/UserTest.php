<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser()
    {
        $user = new User();
        $date = new \DateTimeImmutable('now');
        $hotel = new Hotel();
        $booking = new Booking();

        $user->setEmail('user@gg.com');
        $user->setPassword('123456');
        $user->setFullName('User');
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt($date);
        $user->setUpdatedAt($date);
        $user->setDeletedAt($date);
        $user->setPhone('123456789');
        $user->setHotel($hotel);
        $user->addBooking($booking);
        $user->removeBooking($booking);
        $this->assertEquals($user->getEmail(), 'user@gg.com');
        $this->assertEquals($user->getPassword(), '123456');
        $this->assertEquals($user->getFullName(), 'User');
        $this->assertEquals($user->getRoles(), ['ROLE_USER']);
        $this->assertEquals($user->getCreatedAt(), $date);
        $this->assertEquals($user->getUpdatedAt(), $date);
        $this->assertEquals($user->getId(), null);
        $this->assertEquals($user->getDeletedAt(), $date);
        $this->assertEquals($user->getPhone(), '123456789');
        $this->assertEquals($user->getHotel(), $hotel);
        $this->assertEquals($user->getBookings()->toArray(), []);
        $this->assertEquals($user->getUserIdentifier(), 'user@gg.com');
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertTrue($user->isAdmin());
        $user->setRoles(['ROLE_HOTEL']);
        $this->assertTrue($user->isHotel());
    }

}
