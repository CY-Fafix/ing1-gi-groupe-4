A = [[1, 1], [-3, 1], [1, -1/2]]
b = [4, 3, 1]
c = [1, 1/2]

Anb = [[1, -1], [-1, 1]]
bnb = [3, 5]
cnb = [2, 3]

A2 = [[2, 3],  [3, 1], [-1, -1]]
b2 = [12, 9, -2]
c2 = [1, 2]

#Affiche la matrice ligne par ligne
def affi(mat) :
    for i in range(len(mat)) : print(mat[i])

#l1 <- l1 +coeff*l2
def transfo(m,i1,i2,coeff) :
    for i in range(len(m[0])) :
        m[i1][i]+=coeff*m[i2][i]

# l <- coeff*l
def multicoeff(m,i,coeff) :
    for j in range(len(m[0])) :
        m[i][j]*=coeff

#Chaque coefficient du vecteur est > 0
def supZero(v) :
    for i in v :
        if i < 0 :
            return False
    return True

#Précondition : A est dans la forme canonique
def methodeTableaux(A, b, c) :
    #Création du tableau
    mat = [c + [0 for i in range(len(A)+1)]] #Premiere ligne du tableau
    for i in range(len(A)) :
        memo = [] #Contient la ligne actuelle à ajouter
        for j in range(len(A[0])) :
            memo.append(A[i][j]) #On ajoute A au debut de la ligne
        for j in range(len(A)) :
            if i == j : #On ajoute la matrice identité à la fin de la ligne
                memo.append(1)
            else :
                memo.append(0)
        memo.append(b[i])
        mat.append(memo)
    base = [i + len(A) for i in range(0, len(c))] + [i for i in range(len(A))]
    if supZero(b) : #Origine est admissible
        affi(mat)
        print("Base : ", base)
        simplexe(mat, base)
    else : #Origine non admissible, méthode des deux phases
        phaseUne(mat, base)


def simplexe(mat, base) :
    print("----------Nouvelle itération----------")
    rentre = mat[0][0]
    iRentre = 0
    for i in range(len(mat)-1) :
        if mat[0][i] > rentre :
            rentre = mat[0][i]
            iRentre = i

    #if rentre>0 : #Cas où on cherche une variable sortante
    if rentre > 0 :
        i=1
        while i<len(mat) and (mat[i][iRentre] == 0 or mat[i][len(mat[0])-1]/mat[i][iRentre] <= 0):
            i+=1;
        if i == len(mat) : #Cas ou il n'y a pas de variable sortante, problème non borné
            print("Problème non borné")
        else :
            sort = mat[i][len(mat[0])-1]/mat[i][iRentre]
            iSort = i
            for j in range(i+1, len(mat)) :
                if mat[j][iRentre] != 0 and mat[j][len(mat[0])-1]/mat[j][iRentre] > 0 and mat[j][len(mat[0])-1]/mat[j][iRentre] < sort :
                    sort = mat[j][len(mat[0])-1]/mat[j][iRentre]
                    iSort = j

            #On met le pivot à 1
            multicoeff(mat, iSort, 1/mat[iSort][iRentre])
            #On pivote les lignes
            for i in range(0, len(mat)) :
                if i != iSort :
                    transfo(mat, i, iSort, -mat[i][iRentre])
            affi(mat)

            #Changement de la base
            memo = 0
            for i in range(0, len(base)) :
                if base[i]+1 == iSort :
                    memo = i
            base[memo], base[iRentre] = base[iRentre], base[memo]
            print("Nouvelle base : ", base)

            simplexe(mat, base)
    else : #Pas de variable entrante, solution trouvée
        affi(mat)
        print("Base : ", base)
        print("Optimum : ", -mat[0][len(mat[0])-1])
        res = "Valeur : ("
        for i in range(len(base)) :
            for j in range(len(base)) :
                if j == base[i] :
                    if j+1 >= len(mat) :
                        res += "0"
                    else :
                        res += str(mat[j+1][len(mat[0])-1])
                    if i != len(base)-1 :
                        res += ", "
        print(res + ")")

methodeTableaux(A,b,c)

def phaseUne(mat, base) :
    for i in range(len(mat[0])): #On met des 0 sur la première ligne
        mat[0][i] = 0
    for i in range(len(mat)): #On insère la colonne de -1 à droite de A
        mat[i].append(0)
        for j in range(len(mat[0])-1, len(mat[0])-1-len(mat), -1) :
            mat[i][j] = mat[i][j-1]
        mat[i][len(mat[0])-1-len(mat)] = -1
    #Il faut ajouter delta à la base
    affi(mat)
    print(base)
