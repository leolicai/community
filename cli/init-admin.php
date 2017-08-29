<?php
/**
 * init-admin.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/29
 * Version: 1.0
 */


use Admin\Entity\Adminer;
use Admin\Entity\Group;
use Ramsey\Uuid\Uuid;

require 'bootstrap.php';

$adminEmail = 'admin@admin.com';
$adminPasswd = 'admin';

$existedAdmin = $entityManager->getRepository(Adminer::class)->findOneBy(['adminEmail' => $adminEmail]);
if ($existedAdmin instanceof Adminer) {
    echo 'Administrator has been inited!' . PHP_EOL;
    exit(1);
}


$entityAdmin = new Adminer();

$entityAdmin->setAdminID(Uuid::uuid1()->toString());
$entityAdmin->setAdminEmail($adminEmail);
$entityAdmin->setAdminPasswd(md5($adminPasswd));
$entityAdmin->setAdminActivated(Adminer::ACTIVATED_VALID);
$entityAdmin->setAdminActiveCode('');
$entityAdmin->setAdminDefault(Adminer::DEFAULT_ADMIN);
$entityAdmin->setAdminExpired(new \DateTime("+99 years"));
$entityAdmin->setAdminCreated(new \DateTime());
$entityAdmin->setAdminLevel(Adminer::LEVEL_SUPPER);
$entityAdmin->setAdminLocked(Adminer::LOCKED_INVALID);
$entityAdmin->setAdminStatus(Adminer::STATUS_VALID);
$entityAdmin->setAdminName('Administrator');

$entityGroup = new Group();

$entityGroup->setGroupID(Uuid::uuid1()->toString());
$entityGroup->setGroupDefault(Group::DEFAULT_GROUP);
$entityGroup->setGroupStatus(Group::STATUS_VALID);
$entityGroup->setGroupName('Default Group');
$entityGroup->setGroupCreated(new \DateTime());

$groups = $entityAdmin->getAdminGroups();
$groups->add($entityGroup);
$entityAdmin->setAdminGroups($groups);

$adminers = $entityGroup->getGroupAdminers();
$adminers->add($entityAdmin);
$entityGroup->setGroupAdminers($adminers);

$entityManager->persist($entityAdmin);
$entityManager->persist($entityGroup);
$entityManager->flush();

echo 'Inited administrator and default group.' . PHP_EOL;
echo 'Please use the follow account login' . PHP_EOL;
echo 'E-mail: ' . $adminEmail . PHP_EOL;
echo 'Password: ' . $adminPasswd . PHP_EOL;
echo 'Thanks!' . PHP_EOL;
exit(1);