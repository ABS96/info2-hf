# Lyukkártyás program nyilvántartás
 
# Specifikáció
## Feladat informális leírása
Egy lyukkártyás programokat nyilvántartó adatbázist szeretnék megvalósítani. Az adatbázisban szerepelni fognak a feldolgozandó programok, illetve az azokat megíró személyek. A cél ezen adatbázis karbantartása, ahol az operátor beviheti, ellenőrizheti, és törölheti az általa feldolgozandó programok adatait.

## Elérhető funkciók
Az alkalmazás a következő funkciókat biztosítja:
 * Programok kezelése:
    * Új program létrehozása
    * Meglévő program adatainak módosítása
    * Programok törlése
    * Az adatbázisban tárolt programok listázása
* Személyek kezelése:
    * Új személyek létrehozása
    * Meglévő személyek adatainak módosítása
    * Személyek törlése
    * Az adatbázisban tárolt személyek listázása
## Adatbázis séma
Az adatbázisban a következő entitások és attribútumok szerepelnek:
 * Program: azonosító, tulajdonos azonosítója, prioritás, felvétel dátuma
 * Személy: azonosító, vezetéknév, keresztnév, titulus

A fenti adatok tárolását az alábbi sémával oldjuk meg:
![Séma](./scheme.png "Adatbázis séma")
 
