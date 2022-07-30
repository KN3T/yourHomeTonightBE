<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getImageData() as [$id, $path]) {
            $image = new Image();
            $image->setPath($path);

            $manager->persist($image);
            $this->addReference('image_'.$id, $image);
        }

        $manager->flush();
    }

    private function getImageData(): array
    {
        return [
            [
                1,
                'https://pearlriverhotel.vn/wp-content/uploads/2019/07/pearl-river-hotel-home1.jpg',
            ],
            [
                2,
                'https://tqthotel.com/public/upload/page/chao-mung-quy-khach-den-voi-tqt-hote-1575876650.jpg',
            ],
            [
                3,
                'https://nhatran111.vn/admin/Upload/ImageContent/Sarovar-Hotels-Ahmedabad_20170429091748.jpg',
            ],
            [
                4,
                'https://digital.ihg.com/is/image/ihg/even-hotels-eugene-5405616297-4x3',
            ],
            [
                5,
                'https://statics.vinpearl.com/vinpearl-hotel-rivera-hai-phong-1_1629268262.jpg',
            ],
            [
                6,
                'https://www.merperle.vn/wp-content/uploads/sites/182/2020/05/001f-view-6a.jpg',
            ],
            [
                7,
                'https://thumbs.dreamstime.com/b/hotel-room-beautiful-orange-sofa-included-43642330.jpg',
            ],
            [
                8,
                'https://gos3.ibcdn.com/18f9b55eb22b11eb86440242ac110005.jpg',
            ],
            [
                9,
                'http://www.bedbreakfast.ee/wp-content/uploads/2016/10/hotel-room.jpg',
            ],
            [
                10,
                'http://www.bedbreakfast.ee/wp-content/uploads/2016/10/hotel-room.jpg',
            ],
            [
                11,
                'http://www.bedbreakfast.ee/wp-content/uploads/2016/10/hotel-room.jpg',
            ],
            [
                12,
                'http://www.bedbreakfast.ee/wp-content/uploads/2016/10/hotel-room.jpg',
            ],
            [
                13,
                'http://www.bedbreakfast.ee/wp-content/uploads/2016/10/hotel-room.jpg',
            ],
            [
                14,
                'http://www.bedbreakfast.ee/wp-content/uploads/2016/10/hotel-room.jpg',
            ],
            [
                15,
                'http://www.bedbreakfast.ee/wp-content/uploads/2016/10/hotel-room.jpg',
            ],
        ];
    }
}
