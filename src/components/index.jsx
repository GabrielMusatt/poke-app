import React, { useState, useEffect } from "react";
import {
  StyledPokedex,
  StyledResultSection,
  StyledOwnedSection,
  StyledSearchCard,
} from "./styled";
import PokeSearch from "./Search";
import pokeType from "./pokeTypes";

const Pokedex = () => {
  let results = [];
  const [inputValue, setInputValue] = useState("");
  const [allPokemons, setAllPokemons] = useState([]);
  const [foundPokemons, setFoundPokemons] = useState([]);

  useEffect(() => {
    fetch("https://pokeapi.co/api/v2/pokemon?limit=100000")
      .then((response) => response.json())
      .then((name) => {
        setAllPokemons([...allPokemons, ...name.results]);
      });
    console.log(allPokemons, "allPokemons");
    setAllPokemons(allPokemons);
  }, [inputValue]);

  const handlesPokes = (event) => {
    console.log(event.target.value);
    results = [];
    results = [...allPokemons].filter((pokemon) => {
      if (
        pokemon.name.startsWith(event.target.value) &&
        event.target.value.length > 2
      ) {
        const newPokemon = { ...pokemon };

        let pokemonExist = false;

        fetch(pokemon.url)
          .then((response) => response.json())
          .then((pokeStats) => {
            newPokemon.stats = pokeStats;
            console.log(pokeStats, "stat");
            console.log(newPokemon.stats, "newPokemon.stats");

            console.log(newPokemon, "new");
            console.log(results, "results");

            for (let i = 0; i < results.length; i++) {
              console.log(results[i], "result stats");
              console.log(newPokemon, "newPokemon result");

              if (results[i].name === newPokemon.name) {
                pokemonExist = true;
              }
            }

            if (!pokemonExist) {
              results.push(newPokemon);
              results.sort((a, b) => {
                return a.name < b.name ? -1 : a.name < b.name ? 1 : 0;
              });
            }
            pokemonExist = false;
          });
      } else {
        results = [];
      }
    });
    console.log(results, "results");

    setFoundPokemons(results);
  };

  return (
    <StyledPokedex>
      <PokeSearch
        handlesPokes={handlesPokes}
        inputValue={inputValue}
        setInputValue={setInputValue}
      />
      {foundPokemons && foundPokemons.length ? (
        <StyledResultSection>
          {foundPokemons &&
            foundPokemons.map((pokemon) => {
              return (
                <>
                  {console.log(pokemon, "inside poke")}
                  <StyledSearchCard key={pokemon.stats.id}>
                    <div className="card-content">
                      <img
                        src={pokemon.stats.sprites.front_default}
                        alt={`${pokemon.name} photo`}
                      />
                      <div className="card-description">
                        {pokemon.name.split("-").join(" ")}
                        <p className="pokemon-type">
                          {pokeType(pokemon.stats.types[0].type.name)}
                        </p>
                      </div>
                    </div>
                  </StyledSearchCard>
                </>
              );
            })}
        </StyledResultSection>
      ) : null}
      <StyledOwnedSection>Owned</StyledOwnedSection>
    </StyledPokedex>
  );
};

export default Pokedex;
